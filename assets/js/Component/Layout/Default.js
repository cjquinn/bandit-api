import React from 'react';

const Default = (props) =>
    <div>
        {props.children}
    </div>

Default.propTypes = {
    children: React.PropTypes.element.isRequired
};

export default Default;
