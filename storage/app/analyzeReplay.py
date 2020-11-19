import carball
import gzip
from carball.json_parser.game import Game
from carball.analysis.analysis_manager import AnalysisManager
from carball.analysis.utils.pandas_manager import PandasManager
import pandas
import sys
import os
import json
from replay_positions import ReplayPositions

replay_id = sys.argv[1]
player_id = sys.argv[2]

pl = ""

_json = carball.decompile_replay('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id + '/' + replay_id + '.replay')#, 
                                #output_path='9EB5E5814D73F55B51A1BD9664D4CBF3.json', 
                                #overwrite=True)

# _json is a JSON game object (from decompile_replay)
game = Game()
game.initialize(loaded_json=_json)

analysis_manager = AnalysisManager(game)
analysis_manager.create_analysis()
    
# return the proto object in python
proto_object = analysis_manager.get_protobuf_data()

# return the proto object as a json object
json_object = analysis_manager.get_json_data()

# return the pandas data frame in python
dataframe = analysis_manager.get_data_frame()

# write proto out to a file
# read api/*.proto for info on the object properties

if not os.path.exists('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id):
    os.makedirs('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id)

with open('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id + '/metadata.json', 'w') as fo:
    analysis_manager.write_json_out_to_file(fo)
    
jd = json.dumps(ReplayPositions.create_from_id(dataframe, proto_object, replay_id).__dict__)
with open('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id + '/positions.json', 'w') as fo:
    fo.write(jd)



goals = json_object["gameMetadata"]["goals"]
goal_frames = []
proto_game = json_object
for goal in goals:
    if goal["playerId"]["id"] == player_id:
        goal_frames.append(goal["frameNumber"])

with open('C:/laragon/www/goalviewer/storage/app/replays/' + replay_id + '/goals.json', 'w') as fo:
    fo.write(json.dumps(goal_frames))

print(goal_frames)
#python analyzeReplay.py 6c7b1dc3-176b-4d8a-a3e5-042055574a69 76561198174027955
