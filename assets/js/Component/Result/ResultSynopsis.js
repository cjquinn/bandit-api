import React from 'react';

const ResultSynopsis = () =>
    <dl result>
        <dt>Date:</dt>
        <dd>Tuesday 13th January 2015</dd>

        <dt>Score:</dt>
        <dd>2 - 1</dd>

        <dt>Players:</dt>
        <dd>
            <dl player>
                <dt>Winner:</dt>
                <dd>Alan Baker
                    <dl>
                        <dt>Rating:</dt>
                        <dd>1650</dd>

                        <dt>Level:</dt>
                        <dd>Ninja</dd>

                        <dt>Points gained:</dt>
                        <dd>185</dd>
                    </dl>
                </dd>
            </dl>

            <dl player>
                <dt>Loser:</dt>
                <dd>Brett Southland
                    <dl>
                        <dt>Rating:</dt>
                        <dd>1495</dd>

                        <dt>Level:</dt>
                        <dd>Knight</dd>

                        <dt>Points gained:</dt>
                        <dd>15</dd>
                    </dl>
                </dd>
            </dl>
        </dd>
    </dl>

export default ResultSynopsis;
