import React, {Component} from 'react';
import styles from '../../sass/components/EditableArea.scss'
import {HotKeys} from 'react-hotkeys'

export default class EditableArea extends Component {

    constructor(props) {
        super(props);

        this.state = {
            mode: null,
            selectionBoxOrigin: [props.resizing.left, props.resizing.top],
            selectionBoxTarget: [+props.resizing.left + +props.resizing.width, +props.resizing.top + +props.resizing.height],
        };

        this.startEditing = this.startEditing.bind(this);
        this.getRelativeCoordinates = this.getRelativeCoordinates.bind(this);
        this.getSelectionCoordinates = this.getSelectionCoordinates.bind(this);
        this.dragging = this.dragging.bind(this);
        this.endDrag = this.endDrag.bind(this);
    }

    endDrag(e) {
        this.setState({mode: null});
    }

    startEditing(y, x) {
        this.setState({mode: {y, x}});
    }

    dragging(e) {
        if (!this.state.mode) return;

        const re = e.currentTarget.getBoundingClientRect();


        if (this.state.mode.x === 'right' && this.state.mode.y === 'bottom') {
            this.setState({
                selectionBoxTarget: [e.nativeEvent.clientX - re.left, e.nativeEvent.clientY - re.top],
            });
        } else if (this.state.mode.x === 'left' && this.state.mode.y === 'bottom') {
            this.setState({
                selectionBoxTarget: [this.state.selectionBoxTarget[0], e.nativeEvent.clientY - re.top],
                selectionBoxOrigin: [e.nativeEvent.clientX - re.left, this.state.selectionBoxOrigin[1]],
            });
        } else if (this.state.mode.x === 'right' && this.state.mode.y === 'top') {
            this.setState({
                selectionBoxTarget: [e.nativeEvent.clientX - re.left, this.state.selectionBoxTarget[1]],
                selectionBoxOrigin: [this.state.selectionBoxOrigin[0], e.nativeEvent.clientY - re.top],
            });
        } else if (this.state.mode.x === 'left' && this.state.mode.y === 'top') {
            this.setState({
                selectionBoxOrigin: [e.nativeEvent.clientX - re.left, e.nativeEvent.clientY - re.top],
            });
        }

    }


    getRelativeCoordinates() {

        const {selectionBoxOrigin, selectionBoxTarget} = this.state;

        return {
            left: Math.abs(selectionBoxOrigin[0]),
            top: Math.abs(selectionBoxOrigin[1]),
            height: Math.abs(selectionBoxTarget[1] - selectionBoxOrigin[1]),
            width: Math.abs(selectionBoxTarget[0] - selectionBoxOrigin[0]),
        }
    }


    getSelectionCoordinates() {

        const {selectionBoxOrigin, selectionBoxTarget} = this.state,
            coordinates = this.getRelativeCoordinates();


        let top = coordinates.top,
            left = coordinates.left;

        if (selectionBoxOrigin[1] > selectionBoxTarget[1])
            top -= coordinates.height

        if (selectionBoxOrigin[0] > selectionBoxTarget[0])
            left -= coordinates.width

        return Object.assign(coordinates, {top, left})
    }


    containerStyle() {
        return {
            top: 0,
            left: 0,
            bottom: 0,
            right: 0,
            position: 'absolute',
        }
    }


    render() {
        return (
            <div
                className={styles.wrapper}
                style={this.containerStyle()}
                onMouseMove={this.dragging}
                onMouseUp={this.endDrag}
                //onMouseLeave={this.endDrag}
            >
                <div className={styles.selection}
                     style={this.getRelativeCoordinates()}>
                    <div
                        className={`${styles.resizer} ${this.state.mode ? styles.disabled : ''} ${styles.top} ${styles.left}`}
                        onMouseDown={() => this.startEditing('top', 'left')}/>
                    <div
                        className={`${styles.resizer} ${this.state.mode ? styles.disabled : ''} ${styles.bottom} ${styles.left}`}
                        onMouseDown={() => this.startEditing('bottom', 'left')}/>
                    <div
                        className={`${styles.resizer} ${this.state.mode ? styles.disabled : ''} ${styles.top} ${styles.right}`}
                        onMouseDown={() => this.startEditing('top', 'right')}/>
                    <div
                        className={`${styles.resizer} ${this.state.mode ? styles.disabled : ''} ${styles.bottom} ${styles.right}`}
                        onMouseDown={() => this.startEditing('bottom', 'right')}/>
                </div>
            </div>
        );
    }
}
