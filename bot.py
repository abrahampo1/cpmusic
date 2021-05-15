import pafy
import vlc
import ssl
import time
import requests

ssl._create_default_https_context = ssl._create_unverified_context
def run_forever():
    url_api = "https://musica.asorey.net/api.php"
    video = False
    try:
        while True:
            if video == True:
                video = pafy.new(url)
                best = video.getbestaudio()
                playurl = best.url
                bestvideo = video.getbest()
                videourl = bestvideo.url
                myobj = {
                    'api': '123',
                    'miniatura': video.bigthumb,
                    'url': url,
                    'titulo': video.title,
                    'video': videourl
                }
                x = requests.post(url_api, data=myobj)
                if video.length > 600:
                    print("No amigo, las prefiero pequeñas")
                    print("Terminado rey.")
                    video = False
                    myobj = {
                        'api': '123',
                        'terminado': url
                    }
                    x = requests.post(url_api, data=myobj)
                    print(x.text)
                    run_forever()
                else:
                    print("Menos de 10? buen track bro")
                print(x.text)
                Instance = vlc.Instance()
                player = Instance.media_player_new()
                Media = Instance.media_new(playurl)
                player.set_media(Media)
                player.play()
                print("Reproduciendo...")
                while round(player.get_time()/1000) <= round(player.get_length()/1000)-7:
                    myobj = {
                        'api': '123',
                        'url': url,
                        'tiempo': round(player.get_time()/1000),
                        'total': round(player.get_length()/1000)-7
                    }
                    x = requests.post(url_api, data=myobj)
                    time.sleep(0.1)
                    print(str(round(player.get_time()/1000)) +"/"+ str(round(player.get_length()/1000)-7),end='\r')

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
    except Exception:
        print("Me he crasheado :(, me reinicio al toque.")
        run_forever()
run_forever()
            
