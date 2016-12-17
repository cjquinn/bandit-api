import React from 'react';

import ResultSynopsis from './ResultSynopsis';

const ResultStream = () =>
    <ol>
        {[...Array(5)].map(() => <ResultSynopsis />)}
    </ol>

export default ResultStream;
