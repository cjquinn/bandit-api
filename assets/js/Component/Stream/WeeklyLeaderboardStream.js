import React from 'react';

import PlayerWeeklySynopsis from './PlayerWeeklySynopsis';

const WeeklyLeaderboardStream = () =>
    <ol>
        {[...Array(5)].map(() => <PlayerAllTimeSynopsis />)}
    </ol>

export default WeeklyLeaderboardStream;
