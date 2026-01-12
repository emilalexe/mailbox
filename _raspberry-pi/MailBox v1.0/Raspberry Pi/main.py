import network
import urequests
import machine
import time
import ubinascii

# --- CONFIGURARE WIFI ---
SSID = "REA"
PASSWORD = "rea1234567890"

# --- CONFIGURARE SERVER ---
URL_SERVER = "https://api.exal-script.ro/mailbox/mailbox.php"
API_KEY = "(p1I-&jpq_M1x55[]CTfHKE0"

# Pinul butonului (GP17)
button = machine.Pin(17, machine.Pin.IN, machine.Pin.PULL_UP)

def connect_wifi():
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)
    wlan.connect(SSID, PASSWORD)
    
    print("Se conecteaza la WiFi", end="")
    while not wlan.isconnected():
        print(".", end="")
        time.sleep(1)
    
    # Obține adresa MAC pentru identificare
    mac = ubinascii.hexlify(wlan.config('mac'),':').decode().upper()
    print("\nConectat! MAC:", mac)
    return mac

def trimite_alerta(mac):
    headers = {
        "Authorization": API_KEY,
        "X-Device-MAC": mac
    }
    try:
        print("Trimitere cerere catre server...")
        response = urequests.get(URL_SERVER, headers=headers)
        print("Raspuns server:", response.text)
        response.close()
    except Exception as e:
        print("Eroare la trimitere:", e)

# Pornire
mac_address = connect_wifi()

print("Sistem pregatit. Apasa butonul!")

while True:
    if button.value() == 0:  # Butonul a fost apasat (conectat la GND)
        print("Buton apasat!")
        trimite_alerta(mac_address)
        time.sleep(5)  # Anti-spam
    time.sleep(0.1)