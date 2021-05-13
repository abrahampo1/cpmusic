import pafy
import vlc
import ssl
import time
import requests
url_api = "https://musica.asorey.net/api.php"
video = False
ssl._create_default_https_context = ssl._create_unverified_context
def run_forever():
    try:
        while True:
            if video == True:
                video = pafy.new(url)
                best = video.getbestaudio()
                playurl = best.url
                bestvideo = video.getbest()
                videourl = bestvideo.url
                print(video.length)
                if video.length > 520:
                    print("No amigo, las prefiero pequeñas")
                    exit()
                else:
                    print("Menos de 7? buen track bro")
                myobj = {
                    'api': '123',
                    'miniatura': video.bigthumb,
                    'url': url,
                    'titulo': video.title,
                    'video': videourl
                }
                x = requests.post(url_api, data=myobj)
                print(x.text)
                Instance = vlc.Instance()
                player = Instance.media_player_new()
                Media = Instance.media_new(playurl)
                player.set_media(Media)
                player.play()
                print("Reproduciendo...")
                i = 0
                while i != video.length:
                    myobj = {
                        'api': '123',
                        'url': url,
                        'tiempo': i,
                        'total': video.length
                    }
                    x = requests.post(url_api, data=myobj)
                    time.sleep(1)
                    i += 1
                    print(str(i) +"/"+ str(video.length),end='\r')

                print("Terminado rey.")
                video = False
                myobj = {
                    'api': '123',
                    'terminado': url
                }
                x = requests.post(url_api, data=myobj)
                print(x.text)
            else:
                time.sleep(3)
                print("Esperando URL...")
                myobj = {
                    'api': '123',
                    'necesito': 'url',
                }
                x = requests.post(url_api, data=myobj)
                texto = x.text
                if(texto != ""):
                    video = True
                    url = texto
                    print("Encontré la canción :)")
    except:
        print("Me he crasheado :(, me reinicio al toque.")
        run_forever()

run_forever()
            
