from asyncio.tasks import sleep
from logging import exception
from os.path import split
import discord
import os
import pafy
import ssl
import asyncio
import aiohttp
import certifi
import requests
from discord.ext import commands
from dotenv import load_dotenv
load_dotenv()
ssl_context = ssl.create_default_context(cafile=certifi.where())

# Get the API token from the .env file.
DISCORD_TOKEN = os.getenv("discord_token")
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
async def getbotdata():
    print(";;")
    for guild in client.guilds:
        print('"'+guild.name)
    print(";;")
    await asyncio.sleep(3)

@bot.command(name='join', help='To make the bot leave the voice channel')
async def play(ctx):
    if not ctx.message.author.voice:
        await ctx.send('Tienes que estar en un canal de voz para hacer esto')
        return
    else:
        channel = ctx.message.author.voice.channel
    url_api = "https://musica.asorey.net/api.php"
    server = ctx.message.guild
    try:
        print("Server de discord: "+server.name+"\n")
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
    print("\n")
    url_true = ""
    myobj = {
        'api': '123',
        'necesito_discord': 'url',
    }
    while True:
        x = requests.post(url_api, data=myobj)
        url = x.text
        if url == "":
            await ctx.send("Estoy esperando por una canción...")
        while url == "":
            await asyncio.sleep(1)
            x = requests.post(url_api, data=myobj)
            url = x.text
        url = url.split(";")
        tiempo = url[1]
        url = url[0]
        guild = ctx.message.guild

        # print(playurl)
        # with youtube_dl.YoutubeDL(ydl_opts) as ydl:
        #    file = ydl.extract_info(url, download=True)
        #    path = str(file['title']) + "-" + str(file['id'] + ".mp3")
        if url != url_true:
            try:
                video = pafy.new(url)
                best = video.getbestaudio()
                playurl = best.url
                voice_client.stop()
                FFMPEG_OPTIONS = {
                    'before_options': '-reconnect 1 -reconnect_streamed 1 -reconnect_delay_max 5', 'options': '-vn -ss '+tiempo, }
                try:
                    voice_client.play(discord.FFmpegPCMAudio(playurl, **FFMPEG_OPTIONS, executable='/usr/bin/ffmpeg'))
                    voice_client.source = discord.PCMVolumeTransformer(voice_client.source, 1)
                    await ctx.send(f'**Canción en la radio: **{url}')
                except Exception as e:
                    print(e)
            except Exception as e:
                print("he dao un error xD")
                print(str(e))
        url_true = url
        voice_client.resume()

        await asyncio.sleep(2)


silenciado = False


@bot.command(name='pause', help='This command pauses the song')
async def pause(ctx):
    voice_client = ctx.message.guild.voice_client
    if voice_client.is_playing():
        voice_client.source = discord.PCMVolumeTransformer(
            voice_client.source, 0.01)
        silenciado = True
    else:
        await ctx.send("The bot is not playing anything at the moment.")


@bot.command(name='resume', help='Resumes the song')
async def resume(ctx):
    voice_client = ctx.message.guild.voice_client
    if voice_client.is_playing():
        print("Subiendo volumen")
        voice_client.source = discord.PCMVolumeTransformer(
            voice_client.source, 300)
print("Bot arrancado")
try:
    bot.run(DISCORD_TOKEN)
except Exception as e:
    print(e)
