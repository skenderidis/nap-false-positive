import yaml
import json
import sys


input_file = sys.argv[1]
if 'yml' in input_file:
    mod_file = input_file[:-4] + ".json"
elif 'yml' in input_file:
    mod_file = input_file[:-5] + ".json"
else:
    print("failed matching the extension")
    exit()


                 
with open(input_file, 'r') as file:
   try:
      configuration = json.loads(file)
      with open(mod_file, 'w') as json_file:
         yaml.dump(configuration, json_file, indent=2)
      
      print("success")
   except:
      print("parsing error") 

    
   
