import React from 'react';

import PlayerAllTimeSynopsis from './PlayerAllTimeSynopsis';

const AllTimeLeaderboardStream = () =>
    <ol>
        {[...Array(5)].map(() => <PlayerAllTimeSynopsis />)}
    </ol>

export default AllTimeLeaderboardStream;
