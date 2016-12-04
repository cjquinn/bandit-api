import React from 'react';

const Default = (props) => {
    return (
        <div>
            {props.children}
        </div>
    );
};

Default.propTypes = {
    children: React.PropTypes.element.isRequired
};

export default Default;
