# i give up, sike i'm back now
# https://stackoverflow.com/questions/29692250/restarting-a-thread-in-python
 
# useful links
# https://www.digitalocean.com/community/tutorials/how-to-use-break-continue-and-pass-statements-when-working-with-loops-in-python-3
# https://stackoverflow.com/questions/29692250/restarting-a-thread-in-python
# https://stackoverflow.com/questions/25029537/interrupt-function-execution-from-another-function-in-python
# https://stackoverflow.com/questions/9812344/cancellable-threading-timer-in-python

# useful diagram
# https://www.geeksforgeeks.org/multithreading-python-set-1/#:~:text=Consider%20the%20diagram%20below%20for%20a%20better%20understanding%20of%20how%20the%20above%20program%20works

# most memorable lesson learned 
# https://stackoverflow.com/questions/9768865/python-nonetype-object-is-not-callable-beginner#:~:text=which%20is%20None%20since%20hi()%20doesn%27t%20return%20anything
# this is wrong
# timer = threading.Timer(3.5, half_sec_passed_flag.set)
# this is right
# timer = threading.Timer(3.5, half_sec_passed_flag.set())

# also explain it from high level,gradually to low level
# explaination at bottom

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

# usually use boolean/sleep, 
# but now using event objects because it can be interrupted, unlike sleep
# it's the closest thing to a timer, with a interrupt-able wait() function
keypress_detected_flag = threading.Event()
half_sec_passed_flag = threading.Event()

# cannot use this globally
# after timer finished, need to re-construct new one
# timer = threading.Timer(.5, half_sec_passed_flag.set)

def my_function():
    print("")
    print("My function started")

    # Flag to check if the function should cancel itself
    global keypress_detected_flag
    global half_sec_passed_flag
    
 
    while True:        
        
        # using threading.timer so it can be 
        # ran concurrently
        # on a seperate, cancel-able thread
        # this needs to be re-constructed every keypress
        print("")
        print("starting iteration & threading.timer")
        timer = threading.Timer(.5, half_sec_passed_flag.set)
        timer.start()
        
        
        # inf loop w/o sleep is very inefficient
        
        
        # no need "timer in seconds" args, 
        # wait indefinitely until cancel flag true
        print("waiting")
        keypress_detected_flag.wait()
            
        if half_sec_passed_flag.is_set() == False:
        # if half_sec_passed_boolean == False:
            # restart from top, aka "continue", refer useful links
            print("canceled timer and restarting the loop")
            print("resetted both events")
            keypress_detected_flag.clear()
            half_sec_passed_flag.clear()
            timer.cancel()
            continue
        print("sth")
        if half_sec_passed_flag.is_set() == True:
        # if half_sec_passed_boolean == True:
            # calculate time gap
            # bla
            print("canceled timer and now calculating time gap")
            print("resetted both events")
            keypress_detected_flag.clear()
            half_sec_passed_flag.clear()
            timer.cancel()
            
            continue
            
        
t1 = threading.Thread(target=my_function)

# def multiple_callbacks(e):
#     global keypress_detected_flag
#     keypress_detected_flag.set()
#     my_function(e)

def continue_loop(e):
    global keypress_detected_flag
    keypress_detected_flag.set()
    print("key is pressed")
    
def calc_time_gap():
    global half_sec_passed_flag
    half_sec_passed_flag.set                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
    
t1.start()

# print("t1 with my_function is turns on after press")

# keyboard.on_press(activate_the_goddamn_loop)
keyboard.on_press(continue_loop)




while True:
    time.sleep(60)
    
# what do we want
# 1
# press key
# 2 
# if before 1 second elapsed, reset, otherwise, do sth


# now explain at lower level

# 1 
# if press key, call timer event
# 2 
# if before 1 second elapsed, interrupt timer, otherwise continue
# maybe have 1 thread running time timer , another thread for event listener

# trying something different
# 1 
# if press key, exit some infinite loop
# 2 
# if before 1 second elapsed, reset timer, otherwise continue

# how would i change this simple thing to work with inf loops
# click key 

#     if not yet assign
#         assign
        
        
#     if reclick before 1s:
#         don't re-assign starting time        
#     otherwise:
#         calculate time gap
        
        
#     reset variables
    
# trying to re-write if block

    # while keypress_detected_flag.set() is False
        # just sit here
    
# here should be a splitway, if got out before 1s,should just skip
# maybe can use timer thread to cancel code for time gap calc
# so there's 3 threads, 
# 1 for running infinite loop
# 2 is timer thread for breaking inner loop
# 3 is for thread canceling code for time gap calc

# old stuff but too clingy to remove 
# trying to put here, because threads can't start() after already clear()'
        # actually above is wrong lmao, nvm
        # keypress_detected_flag = threading.Event()
        # half_sec_passed_flag = threading.Event()
        
# inf loop w/o sleep is very inefficient
        # try:
        # while keypress_detected_flag.is_set() == False:
        #     # Simulate some work in my_function
        #     print("running")
        #     time.sleep(0.2)