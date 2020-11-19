import Button from "@material-ui/core/Button"
import Grid from "@material-ui/core/Grid"
import React, { Component } from "react"

import {
  addPlayPauseListener,
  dispatchPlayPauseEvent,
  PlayPauseEvent,
  removePlayPauseListener,
} from "../../eventbus/events/playPause"
import { GameManager } from "../../managers/GameManager"
import CameraManager from "../../managers/CameraManager"
import SceneManager from "../../managers/SceneManager"




interface Props {}

interface State {
  paused: boolean
}

export default class PlayControls extends Component<Props, State> {
  constructor(props: Props) {
    
    super(props)
    this.state = {
      
      paused: false,
    }

    addPlayPauseListener(this.onPlayPause)
  }

  

  getData() {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const replay_id = params.get('replay_id');
    const player_id = params.get('player_id');
    var a = params.get('goals')?.split(',');
    var a2 = require("C:/laragon/www/goalviewer/CustomWebReplayViewer/docs/examples/" + replay_id + "/metadata.json");
    return [a, a2, replay_id, player_id];
  }

  getGoals() {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    var a = params.get('goals');
    return a;
  }

  componentWillUnmount() {
    removePlayPauseListener(this.onPlayPause)
  }

  setPlayPause = () => {
    const isPaused = this.state.paused
    dispatchPlayPauseEvent({
      paused: !isPaused,
    })
  }

  onPlayPause = ({ paused }: PlayPauseEvent) => {
    this.setState({
      paused,
    })
  }

  render() {
    var count = 0
    const { clock } = GameManager.getInstance()
    const onResetClick = () => clock.setFrame(0)
    const onNextClick = () => {
      
      var [a, a2, replay_id, player_id] = this.getData();
      var targetPlayerName = '';
        for(var i=0; i < Object.keys(a2.players).length; i++) {
          if(a2.players[i].id.id == player_id) {
            targetPlayerName = a2.players[i].name;
          }
        }
        clock.setFrame(a[count] - 150)
        const { players, field } = SceneManager.getInstance()
        for (const player of players) {
            if(player.playerName == targetPlayerName) {
              CameraManager.getInstance().setCameraLocation(player)
            }
        }
      
      
      count += 1
    }
    return (
      <Grid container spacing={3}>
        <Grid item>
          <Button variant="outlined" onClick={this.setPlayPause}>
            {this.state.paused ? "Play" : "Pause"}
          </Button>
        </Grid>
        <Grid item>
          <Button variant="outlined" onClick={onResetClick}>
            Reset
          </Button>
        </Grid>
        <Grid item>
          <Button variant="outlined" onClick={onNextClick}>
            Next goal {this.getGoals()}
          </Button>
        </Grid>
      </Grid>
    )
  }
}
