import React from 'react';

import ResultSynopsis from './ResultSynopsis';

const Results = () =>
    <ol>
        {[...Array(5)].map(() => <ResultSynopsis />)}
    </ol>

export default Results;
