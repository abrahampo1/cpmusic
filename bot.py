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
                currenttime = round(player.get_time()/1000)
                while round(player.get_length()/1000) < 1 :
                    print("He detectado que la longitud del video es incorrecta, voy a esperar 1 segundo")
                    time.sleep(1)
                    print(playurl)
                    time.sleep(1)
                    Instance.vlm_del_media(playurl)
                    Instance = vlc.Instance()
                    player = Instance.media_player_new()
                    Media = Instance.media_new(playurl)
                    player.set_media(Media)
                    player.play()
                    time.sleep(5)
                    print(player.get_length())
                print(video.length)
                print(round(player.get_length()/1000))
                if(video.length == 0):
                    print("Mis intentos han sido fallidos, una pena, voy a poner la siguiente canción, pero antes voy a cerciorarme de que no puedo hacer nada más")
                    time.sleep(1)
                    video = pafy.new(url)
                    if(video.length == 0):
                        print("na, ni de coña, pongo la siguiente al toque")
                        time.sleep(1)
                print("Reproduciendo ('"+video.title+"')")
                while currenttime < video.length:
                    currenttime = round(player.get_time()/1000)
                    myobj = {
                        'api': '123',
                        'url': url,
                        'tiempo': currenttime,
                        'total': round(player.get_length()/1000)
                    }
                    x = requests.post(url_api, data=myobj)
                    time.sleep(0.1)
                    Ended = 6
                    if player.get_state() == Ended:
                        print("He detectado que no hay más canción, forzando la siguiente...")
                        break
                    print(str(currenttime) +"/"+ str(round(player.get_length()/1000)) + " Estado: "+str(player.get_state()),end=';')

                print("Terminado rey.")
                video = False
                myobj = {
                    'api': '123',
                    'terminado': url
                }
                x = requests.post(url_api, data=myobj)
                print(x.text)
            else:
                print("Esperando URL...")
                time.sleep(3)
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
    except Exception as e:
        print("Me he crasheado :(, me reinicio al toque. " + str(e))
        run_forever()
run_forever()
            
