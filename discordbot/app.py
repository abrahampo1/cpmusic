from asyncio.tasks import sleep
from os.path import split
import discord
import os
import pafy
import ssl
import asyncio
import requests
from discord.ext import commands
from dotenv import load_dotenv
load_dotenv()

# Get the API token from the .env file.
DISCORD_TOKEN = os.getenv("discord_token")
ssl._create_default_https_context = ssl._create_unverified_context
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
bot = commands.Bot(command_prefix='asorey ',intents=intents)                                 

@bot.command(name='join', help='To make the bot leave the voice channel')
async def play(ctx):
    if not ctx.message.author.voice:
        await ctx.send('Tienes que estar en un canal de voz para hacer esto')
        return
    else:
        channel = ctx.message.author.voice.channel
    url_api = "https://musica.asorey.net/api.php"
    voice_client = await channel.connect()
    url_true = ""
    myobj = {
            'api': '123',
            'necesito_discord': 'url',
            }
    while True:
        x = requests.post(url_api, data=myobj)
        url = x.text
        while url=="":
            x = requests.post(url_api, data=myobj)
            url = x.text
            await ctx.send("Estoy esperando por una canción...")
            await asyncio.sleep(5)
        url = url.split(";")
        tiempo = url[1]
        url = url[0]
        guild = ctx.message.guild
        
        #print(playurl)
        #with youtube_dl.YoutubeDL(ydl_opts) as ydl:
        #    file = ydl.extract_info(url, download=True)
        #    path = str(file['title']) + "-" + str(file['id'] + ".mp3")
        if url != url_true:
            try:
                video = pafy.new(url)
                best = video.getbestaudio()
                playurl = best.url
                voice_client.play(discord.FFmpegPCMAudio(playurl, options='-ss '+tiempo))
                voice_client.source = discord.PCMVolumeTransformer(voice_client.source, 1)
                await ctx.send(f'**Canción en la radio: **{url}')
            except:
                print("he dao un error xD")
        url_true = url
        voice_client.resume()
        
        await asyncio.sleep(2)

silenciado = False
@bot.command(name='pause', help='This command pauses the song')
async def pause(ctx):
    voice_client = ctx.message.guild.voice_client
    if voice_client.is_playing():
        voice_client.source = discord.PCMVolumeTransformer(voice_client.source, 0.01)
        silenciado = True
    else:
        await ctx.send("The bot is not playing anything at the moment.")
@bot.command(name='resume', help='Resumes the song')
async def resume(ctx):
    voice_client = ctx.message.guild.voice_client
    if voice_client.is_playing():
        print("Subiendo volumen")
        voice_client.source = discord.PCMVolumeTransformer(voice_client.source, 300)

bot.run(DISCORD_TOKEN)