import React from 'react';
import ReactTooltip from 'react-tooltip'

export default class InfoWidget extends React.Component {
    render() {
        return (
            <li>
                <a href="#">
                    <button className="btn btn-info" data-tip
                            data-for="InfoTip"
                            data-place="left"
                            data-type="light"
                            data-effect="float"
                            data-border={true}
                            data-html={true}
                            data-multiline={true}
                            data-delay-hide={300}
                            data-delay-show={100}
                    >
                        {this.props.title}
                    </button>
                </a>
                <ReactTooltip id="InfoTip" className="tooltip-info">
                    {this.props.text}
                </ReactTooltip>
            </li>
        );
    }
}