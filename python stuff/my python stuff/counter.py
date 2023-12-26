# finally finished on 3:19 pm 26/12/2023

from ast import If, With
from io import open_code
from posixpath import splitext
# from symbol import with_stmt
import keyboard
import csv
import datetime
from datetime import date
import time
import os
from decimal import Decimal
import threading
from threading import Event



# assigning these 4 with latest value
# https://realpython.com/python-data-types/#raw-strings:~:text=Remove%20ads-,Raw,-Strings
# https://jacobnarayan.com/blogs/how-to-find-the-most-recent-file-in-a-directory-in-python
directory_path = r'C:\Users\asus\Downloads\to be added to github =3\my stuff =3\python stuff\my python stuff'
most_recent_file = None
most_recent_time = 0
# iterate over the files 
for entry in os.scandir(directory_path):
    root_ext = os.path.splitext(entry)
    if root_ext [1] == ".csv":
        if entry.is_file():
            # get the modification time 
            mod_time = entry.stat().st_mtime_ns
            if mod_time > most_recent_time:
                # update most recent file & time modification 
                most_recent_file = entry.name
                most_recent_time = mod_time
                print(most_recent_file)
                print(most_recent_time)

# mashallah ya habibi this datetime.datetime thing is a mouthful, and insane precision, have to divide
recorded_day = datetime.datetime.fromtimestamp(most_recent_time/1000000000).strftime('%Y-%m-%d')
recorded_day = str(recorded_day)
print(f"today is {date.today()} and what's recorded is {recorded_day}")
with open(most_recent_file, mode='r', newline='') as file:
    # https://stackoverflow.com/questions/24946640/removing-r-n-from-a-python-list-after-importing-with-readlines#:~:text=textfile).read().splitlines()-,Or,-even%20better%3A
    lines_in_file = file.read().splitlines()
    last_line = lines_in_file[-1]
    last_line = last_line.split(",")

    # these 4 are from last line from most recent file, 
    # if day reset, will be added to cumulative
    # if same day, will continue be used
    keystroke_count = int(last_line[2])
    recorded_time_elapsed_before_re_execution = round(Decimal(last_line[3]),2)
    cumulative_keystroke_count = int(last_line[4])
    cumulative_time_elapsed = round(Decimal(last_line[5]),2)
    print(f"last line is {last_line}")

# variable set for logic for recording time_elapsed
start_typing_time = 0
end_typing_time = 0
time_elapsed = 0
is_STT_already_set = False
keypress_detected_flag = threading.Event()
# initializing this for file writer loop
recorded_time_elapsed = 0

def now():
    return Decimal(round(time.time(),3))

def calc_time_gap():
    
    print("calc-ing")
    # since threading.timer calls this func, 
    # it's like there's already a sleep here
    # time.sleep(.5)
    
    # for detectiing events (keypress & half_sec_passed)
    global is_STT_already_set
    global keypress_detected_flag
    
    # for time gap calc
    global start_typing_time
    global end_typing_time
    global time_elapsed
    
    # only do this before resetting
    time_elapsed += end_typing_time - start_typing_time

    # reset
    start_typing_time = 0 
    end_typing_time = 0
    is_STT_already_set = False
    keypress_detected_flag.clear()    

# counter function placed here because it's a callback function
def count_keystrokes():
    
    # for keystroke
    global keystroke_count


    # for detecting events (keypress)
    global is_STT_already_set
    global keypress_detected_flag
    
    # for time gap calc
    global start_typing_time
    global end_typing_time
    global time_elapsed
    
    # inf loop, usually used by always-active thread, with wait() inside it, to pause it
    while True:
        
        
        # using threading.timer so it can be 
        # ran concurrently
        # on a seperate, cancel-able thread
        # timer needs to be re-constructed/resetted every keypress
        timer = threading.Timer(.5, calc_time_gap)
        timer.start()
        
        # wait indefinitely until cancel flag true
        keypress_detected_flag.wait()
        
        keystroke_count +=1
        
        # if already calc_time_gap & already reset
        if is_STT_already_set == False:
            print("Setting STT")
            start_typing_time = now()
            is_STT_already_set = True
            
        # end typing time, refershed every keypress
        end_typing_time = now()
        
        # cancel timer & flag before reseting it
        keypress_detected_flag.clear()
        # bruh typo-ed timer.cancel, without (), bruh
        timer.cancel()
        
recorder = threading.Thread(target=count_keystrokes)
recorder.start()
        
# i think have to use this,
# because 
# if direct on.press(keypress_detected_flag.set())
# the method ".set()" will receive the "e" argument, so its weird
def keypress_detected_method(e):
    global keypress_detected_flag
    keypress_detected_flag.set()

keyboard.on_press(keypress_detected_method)


print("Starting Now")


while True:
    
    refreshed_day = str(date.today())

    #if new day
    if refreshed_day != recorded_day:
        
        print("it's a new day")
        recorded_day = refreshed_day

        # assigning this ,from the day before, before adding it to cumulative
        refreshed_time_elapsed = Decimal(round(time_elapsed/60,2)) + recorded_time_elapsed_before_re_execution

        # update these 2 before resetting
        cumulative_keystroke_count += keystroke_count
        cumulative_time_elapsed += refreshed_time_elapsed

        # don't forget to reset recorded_time_elapsed 
        keystroke_count = 0
        time_elapsed = 0    
        refreshed_time_elapsed = 0
        recorded_time_elapsed = 0
        recorded_time_elapsed_before_re_execution = 0
        


    time.sleep(90)
    # assigning this after reset and sleep
    refreshed_time_elapsed = Decimal(round(time_elapsed/60,2)) + recorded_time_elapsed_before_re_execution


    filename = f"keystroke_count_{refreshed_day}.csv"
    timeNow = datetime.datetime.now()
    timeNow = timeNow.strftime("%H:%M:%S")


    if refreshed_time_elapsed > recorded_time_elapsed:
        recorded_time_elapsed = refreshed_time_elapsed
        with open(filename, mode='a', newline='') as file:
            print(f"now open {filename} at {timeNow}")
            writer = csv.writer(file)
            writer.writerow([])
            writer.writerow([refreshed_day, 
                             timeNow, 
                             keystroke_count, 
                             refreshed_time_elapsed, 
                             cumulative_keystroke_count, 
                             cumulative_time_elapsed])


# old version

# # Initialize vars for counter method
# old_keystroke_time = 0
# new_keystroke_time = 0
# keystroke_time_gap = 0
# time_elapsed = 0

# def count_keystrokes(e):

#     # global old_keystroke_time
#     # global new_keystroke_time
#     # global keystroke_time_gap

#     global keystroke_count

#     global start_typing_time
#     global end_typing_time
#     global typing_time_elapsed
#     global time_elapsed
    
#     # global time_elapsed

#     # global cumulative_keystroke_count 
#     # global cumulative_time_elapsed 

#     now = Decimal(round(time.time(),3))

#     # new_keystroke_time = Decimal(round(time.time(),3))
 
#     # keystroke_time_gap = new_keystroke_time - old_keystroke_time

#     # if keystroke_time_gap  < 1:
#     #     time_elapsed += keystroke_time_gap
 
#     # old_keystroke_time = new_keystroke_time



# # Check for the stop key
# if keyboard.is_pressed(stop_key):

#     #zarif's added block
#     filename = f"keystroke_count_{today}.csv"
#     with open(filename, mode='a', newline='') as file:
#         writer = csv.writer(file)
#         writer.writerow([today, timeNow, keystroke_count])
    
#     exit




# # Unregister the listener
# keyboard.unhook_all()

# # Display the result
# print(f"Total keystrokes on {today}: {keystroke_count} (final result)")

