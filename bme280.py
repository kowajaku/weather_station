import bme280
import smbus2
import wysylanie_serwer
import i2c_lcd
from time import sleep

port = 1
address = 0x77
bus = smbus2.SMBus(port)
lcd = i2c_lcd.lcd()


bme280.load_calibration_params(bus,address)

while True:
    bme280_data = bme280.sample(bus,address)
    humidity  = round(bme280_data.humidity,2)
    pressure  = round(bme280_data.pressure,2)
    ambient_temperature = round(bme280_data.temperature,2)
    print(ambient_temperature,pressure,humidity)
    wysylanie_serwer.wyslij_dane("192.168.151.94",ambient_temperature,pressure,humidity)
    lcd.lcd_clear()
    lcd.lcd_display_string(str(ambient_temperature),1)
    lcd.lcd_display_string(str(pressure),2)
    lcd.lcd_display_string(str(humidity),3)


    sleep(10)
