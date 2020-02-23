import React, {Component} from 'react';
import styles from '../../sass/components/SelectableArea.scss'

export default class ZoomableArea extends Component {

    constructor(props) {
        super(props);

        this.state = {
            dragging: false,
        };

        this.zoomIn = this.zoomIn.bind(this);
        this.zooming = this.zooming.bind(this);

        this.beginDrag = this.beginDrag.bind(this);
        this.endDrag = this.endDrag.bind(this);
        this.dragging = this.dragging.bind(this);
    }

    zoomIn(e) {
        this.props.onZoomIn()
    }

    zooming(e) {
        if (e.deltaY === 0) return;

        e.preventDefault();

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

    beginDrag(e) {
        this.setState({dragging: true})
    }

    endDrag(e) {
        this.setState({dragging: false})
    }

    dragging(e) {
        if(!this.state.dragging) return;

        this.props.onMoving(e.movementX, e.movementY)

    }

    render() {
        return (
            <div style={this.containerStyle()}
                 onDoubleClick={this.zoomIn}
                 onMouseLeave={this.endDrag}
                 onMouseDown={this.beginDrag}
                 onMouseUp={this.endDrag}
                 onMouseMove={this.dragging}
                 onWheel={this.zooming}/>
        );
    }
}
