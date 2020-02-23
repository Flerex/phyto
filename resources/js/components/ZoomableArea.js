import React, {Component} from 'react';
import styles from '../../sass/components/SelectableArea.scss'

export default class ZoomableArea extends Component {

    constructor(props) {
        super(props);

        this.zoomIn = this.zoomIn.bind(this);
        this.zooming = this.zooming.bind(this);
    }

    zoomIn(e) {
        this.props.onZoomIn()
    }

    zooming(e) {
        if (e.deltaY === 0) return;

        e.preventDefault()

        const mode = e.deltaY < 0 ? 'up' : 'down';

        this.props.onZooming(mode, e.deltaY)
    }

    containerStyle() {
        return {
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            position: 'absolute',
            display: this.props.disabled ? 'none' : 'block',
        }
    }

    render() {
        return (
            <div style={this.containerStyle()}
                 onDoubleClick={this.zoomIn}
                 onWheel={this.zooming}/>
        );
    }
}
