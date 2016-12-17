import React from 'react';
import {hashHistory, IndexRoute, Route, Router} from 'react-router';

import Default from './Layout/Default';
import Dashboard from './Dashboard/Dashboard';

// Auth
import ActivateAccount from './Auth/ActivateAccount';
import Login from './Auth/Login';
import RequestPasswordReset from './Auth/RequestPasswordReset';
import ResetPassword from './Auth/ResetPassword';

// Club
import ClubSettings from './Club/ClubSettings';
import CreateClub from './Club/CreateClub';
import InvitePlayer from './Club/InvitePlayer';

// Dispute
import Dispute from './Dispute/Dispute';
import Disputes from './Dispute/Disputes';

// Leaderboard
import Leaderboard from './Leaderboard/Leaderboard';

// Player
import Players from './Player/Players';
import Player from './Player/Player';
import PlayerSettings from './Player/PlayerSettings';

// Result
import AddWins from './Result/AddWins';
import Result from './Result/Result';
import Results from './Result/Results';

const App = () => (
    <Router history={hashHistory}>
        <Route path="/" component={Default}>
            <IndexRoute component={Dashboard} />

            // Auth
            <Route path="activate-account" component={ActivateAccount} />
            <Route path="login" component={Login} />
            <Route path="request-password-reset" component={RequestPasswordReset} />
            <Route path="reset-password" component={ResetPassword} />

            // Club
            <Route path="club-settings" component={ClubSettings} />
            <Route path="create-club" component={CreateClub} />
            <Route path="invite-player" component={InvitePlayer} />

            // Dispute
            <Route path="disputes" component={Disputes} />
            <Route path="dispute/:id" component={Dispute} />

            // Leaderboard
            <Route path="leaderboard" component={Leaderboard} />

            // Player
            <Route path="players" component={Players} />
            <Route path="players/:id" component={Player} />
            <Route path="settings" component={PlayerSettings} />

            // Result
            <Route path="add-wins" component={AddWins} />
            <Route path="results" component={Results} />
            <Route path="results/:id" component={Result} />
        </Route>
    </Router>
);

export default App;
