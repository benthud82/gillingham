import schedule
import os
import time

def job():
    os.system("D:\\xampp\\htdocs\\gillingham\\MySQLUpdates\\update_GILL.bat")
    print("Success! update_GILL.bat")

schedule.every().day.at("21:30").do(job)

while True:
    schedule.run_pending()
    time.sleep(1)
