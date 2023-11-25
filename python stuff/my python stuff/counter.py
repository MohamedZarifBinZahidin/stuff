import keyboard
import csv
import datetime
from datetime import date
import time

# Initialize counter and date
keystroke_count = 0
today = date.today()
# holy crap what a mouthful
timeNow = datetime.datetime.now()

# Callback function to count keystrokes
def count_keystrokes(e):
    global keystroke_count
    keystroke_count += 1

# Register the keystroke listener
keyboard.on_press(count_keystrokes)

# Define the stop key
stop_key = 'esc'  # Change to any key you prefer



print("Press any key to start recording keystrokes. Press Enter to stop and save the result...")

    
for x in range(1, (24*60)):
    
    time.sleep(90)

    # Save the result to CSV without removing existing contents
    filename = f"keystroke_count_{today}.csv"
    with open(filename, mode='a', newline='') as file:
        writer = csv.writer(file)
        writer.writerow([])
        writer.writerow([today, timeNow.time(), keystroke_count])



# Check for the stop key
if keyboard.is_pressed(stop_key):

    #zarif's added block
    filename = f"keystroke_count_{today}.csv"
    with open(filename, mode='a', newline='') as file:
        writer = csv.writer(file)
        writer.writerow([today, timeNow, keystroke_count])
    
    exit




# Unregister the listener
keyboard.unhook_all()

# Display the result
print(f"Total keystrokes on {today}: {keystroke_count} (final result)")

