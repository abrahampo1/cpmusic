import pafy
import vlc
import ssl
import time
import requests
url_api = "http://localhost/cpmusic/api.php"
video = False
ssl._create_default_https_context = ssl._create_unverified_context
while True:
    if video == True:
        video = pafy.new(url)
        best = video.getbestaudio()
        playurl = best.url
        print(video.length)
        if video.length > 420:
            print("No amigo, las prefiero pequeñas")
            exit()
        else:
            print("Menos de 7? buen track bro")
        Instance = vlc.Instance()
        player = Instance.media_player_new()
        Media = Instance.media_new(playurl)
        player.set_media(Media)
        player.play()
        print("Reproduciendo...")
        myobj = {
            'api': '123',
            'miniatura': video.bigthumb,
            'url': url,
            'titulo': video.title
        }
        x = requests.post(url_api, data=myobj)
        time.sleep(video.length)
        print("Terminado rey.")
        video = False
        myobj = {
            'api': '123',
            'terminado': url
        }
        x = requests.post(url_api, data=myobj)
    else:
        time.sleep(3)
        print("Esperando URL...")
        myobj = {
            'api': '123',
        }
        x = requests.post(url_api, data=myobj)
        texto = x.text
        if(texto != ""):
            video = True
            url = texto
            print("Encontré la canción :)")
    
