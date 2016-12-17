import React from 'react';

import PlayerSynopsis from './PlayerSynopsis';

const Players = () =>
    <ol>
        {[...Array(5)].map(() => <PlayerSynopsis />)}
    </ol>

export default Players;
