import React from 'react';

import PlayerSynopsis from './PlayerSynopsis';

const PlayerStream = () =>
    <ol>
        {[...Array(5)].map(() => <PlayerSynopsis />)}
    </ol>

export default PlayerStream;
