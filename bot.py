import pafy
import vlc
import ssl
import time
import requests
 
ssl._create_default_https_context = ssl._create_unverified_context
url_api = "https://musica.asorey.net/api.php"
api = ""
def generarapi():
    print("Veo que no tienes una clave API... Vamos a añadirla.")
    myobj = {
        'getapi': "",
    }
    print("Estoy solicitandole un API a CEPIA...")
    x = requests.post(url_api, data=myobj)
    print("Tu nuevo API es :" + x.text)
    with open('blockchain.txt', 'w') as f:
        f.write(x.text)
    print("La he guardado para acordarme de ella.")
    api = x.text
    return api
try:
    print("Leyendo clave API...")
    with open('blockchain.txt') as f:
        lines = f.readlines()
    print("¡Clave API leida y es: "+lines[0])
    myobj = {
        'api': lines[0],
        'test': "testapi"
    }
    print("Voy a confirmar que este API es válido, dame un momento...")
    x = requests.post(url_api, data=myobj)
    if(x.text == "OK"):
        print("API verificada correctamente, adelante amigo")
        api = lines[0]
    else:
        print("Este API no es correcto")
        api = generarapi()

except Exception as e:
    api = generarapi()
def run_forever():
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
                    'api': api,
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
                intentos = 0
                while round(player.get_length()/1000) < 1 :
                    print("He detectado que la longitud del video es incorrecta, voy a esperar 1 segundo")
                    time.sleep(1)
                    Media = Instance.media_new(playurl)
                    player.set_media(Media)
                    player.play()
                    time.sleep(5)
                    intentos += 1
                    if intentos == 5:
                        break
                    print(player.get_length())
                print(str("Reproduciendo ('"+video.title+"')").encode("utf-8"))
                if round(player.get_length()/1000) < 1:
                    print("Como el audio parece que no funciona, voy a poner el video...")
                    Media = Instance.media_new(videourl)
                    player.set_media(Media)
                    player.play()
                while currenttime < video.length:
                    currenttime = round(player.get_time()/1000, 3)
                    myobj = {
                        'api': '123',
                        'url': url,
                        'tiempo': currenttime,
                        'total': round(player.get_length()/1000)
                    }
                    x = requests.post(url_api, data=myobj)
                    if x.text == "pause":
                        if player.get_state() == 3:
                            player.pause()
                        print("Estoy pausado, no roto")
                    if x.text == "play":
                        if player.get_state() == 4:
                            player.play()
                            print(str("Reproduciendo ('"+video.title+"')").encode("utf-8"))
                    if x.text == "next":
                        player.stop()
                        print("He recibido un next, cambio.")
                        break
                    Ended = 6
                    if player.get_state() == Ended:
                        print("He detectado que no hay más canción, forzando la siguiente...")
                        break
                    if player.get_state() == 3:
                        print(str(currenttime) +"/"+ str(round(player.get_length()/1000)) + " Estado: "+str(player.get_state()),end=';')
                    time.sleep(0.1)
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
            
