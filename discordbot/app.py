from asyncio.tasks import sleep
from json.decoder import JSONDecoder
from logging import exception
from os.path import split
import discord
import os
import ssl
import asyncio
import aiohttp
import certifi
import requests
import json
from discord.ext import commands
from dotenv import load_dotenv
load_dotenv()
ssl_context = ssl.create_default_context(cafile=certifi.where())

# Get the API token from the .env file.
DISCORD_TOKEN = os.getenv("discord_token")
APITOKEN = os.getenv("apitoken")
ydl_opts = {
    'format': 'bestaudio/best',
    'postprocessors': [{
        'key': 'FFmpegExtractAudio',
        'preferredcodec': 'mp3',
        'preferredquality': '192',

    }],
}

intents = discord.Intents().all()
client = discord.Client(intents=intents)

bot = commands.Bot(command_prefix='asorey ', intents=intents,
                   connector=aiohttp.TCPConnector(ssl=False))

@bot.command(name='join', help='Haz que el bot se una a tu canal')
async def play(ctx):
    if not ctx.message.author.voice:
        await ctx.send('Tienes que estar en un canal de voz para hacer esto')
        return
    else:
        channel = ctx.message.author.voice.channel
    url_api = "https://musica.asorey.net/api.php"
    server = ctx.message.guild
    try:
        print("Server de discord: "+server.name)
        nombre = server.name
    except:
        nombre = "Error en el nombre del servidor, contiene caracteres no permitidos en UNIX"
    try:
        voice_client = await channel.connect()
        print('Me he conectado a: '+nombre)
    except:
        voice_client = await server.disconnect()
        await asyncio.sleep(1)
        voice_client = await channel.connect()
        print('Me he conectado a: '+nombre)
    id_true = ""
    myobj = {
        'api': '123',
        'necesito_discord': 'url',
    }
    while True:
        x = requests.post(url_api, data=myobj)
        url = x.text
        if url == "":
            await ctx.send("Estoy esperando por una canción...")
            await bot.change_presence(activity=discord.Activity(type=discord.ActivityType.listening, name="Esperando por una cancion en https://musica.asorey.net"))
        while url == "":
            await asyncio.sleep(1)
            x = requests.post(url_api, data=myobj)
            url = x.text
        print(url)
        url = json.loads(url)
        tiempo = url["tiempo"]
        id = url["id"]
        url = url["songurl"]
        title = url["title"]
        guild = ctx.message.guild
        if id != id_true:
            try:
                playurl = url
                voice_client.stop()
                FFMPEG_OPTIONS = {
                    'before_options': '-reconnect 1 -reconnect_streamed 1 -reconnect_delay_max 5', 'options': '-vn -ss '+tiempo, }
                try:
                    voice_client.play(discord.FFmpegPCMAudio(playurl, **FFMPEG_OPTIONS, executable='/usr/bin/ffmpeg'))
                    voice_client.source = discord.PCMVolumeTransformer(voice_client.source, 1)
                    await ctx.send(f'**Canción en la radio: **{title}')
                    await bot.change_presence(activity=discord.Activity(type=discord.ActivityType.listening, name=title))
                except Exception as e:
                    print(e)
            except Exception as e:
                print("he dao un error xD")
                print(str(e))
        id_true = id
        voice_client.resume()

        await asyncio.sleep(2)


silenciado = False


#@bot.command(name='play', help='Uso "play [url] [instagram(opcional)]"')
async def pause(ctx, url, insta):
    url_api = "https://musica.asorey.net/api.php"
    voice_client = ctx.message.guild.voice_client
    videoid = url.split("https://www.youtube.com/watch?v=")
    if videoid[1] != "":
        if insta != "":
            insta = insta.replace("<", "")
            insta = insta.replace(">", "")
            insta = insta.replace('"', "")
            await ctx.send('Gracias '+insta+' por el aporte')
        else:
            insta = ""
        video = "https://www.youtube.com/watch?v=" + videoid[1]
        myobj = {
                    'api': '123',
                    'proponer': video,
                    'insta': insta,
                }
        x = requests.post(url_api, data=myobj)
        texto = x.text
        await ctx.send(texto)
    else:
        await ctx.send("Pibe, tienes que mandar un url de youtube :)")
    
print("Bot arrancado")
try:
    bot.run(DISCORD_TOKEN)
except Exception as e:
    print(e)
