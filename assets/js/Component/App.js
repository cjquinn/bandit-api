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

// Leaderboard
import Leaderboard from './Leaderboard/Leaderboard';

// Player
import Player from './Player/Player';
import Players from './Player/Players';
import PlayerSettings from './Player/PlayerSettings';

// Result
import AddWins from './Result/AddWins';
import Result from './Result/Result';
import Results from './Result/Results';

const App = () => {
    return (
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
                // Leaderboard
                <Route path="leaderboard" component={Leaderboard} />
                // Player
                <Route path="/players" component={Default}>
                    <IndexRoute component={Players} />
                    <Route path=":id" component={Player} />
                </Route>
                <Route path="/settings" component={PlayerSettings} />
                // Result
                <Route path="/add-wins" component={AddWins} />
                <Route path="/results" component={Default}>
                    <IndexRoute component={Results} />
                    <Route path=":id" component={Result} />
                </Route>
            </Route>
        </Router>
    );
};

export default App;
