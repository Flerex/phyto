import React, {Component} from 'react';
import styles from '../../../sass/components/Boxer/EditableArea.scss'
import {pascalCase} from 'pascal-case';

export default class EditableArea extends Component {

    constructor(props) {
        super(props);

        this.minimumSize = 5;

        this.initialState = {
            mode: null,
            selectionBoxOrigin: [props.box.left, props.box.top],
            selectionBoxTarget: [+props.box.left + +props.box.width, +props.box.top + +props.box.height],
        };

        this.state = {
            mode: null,
            selectionBoxOrigin: [props.box.left, props.box.top],
            selectionBoxTarget: [+props.box.left + +props.box.width, +props.box.top + +props.box.height],
        };

        this.startEditing = this.startEditing.bind(this);
        this.getRelativeCoordinates = this.getRelativeCoordinates.bind(this);
        this.getSelectionCoordinates = this.getSelectionCoordinates.bind(this);
        this.dragging = this.dragging.bind(this);
        this.endDrag = this.endDrag.bind(this);

        this.manageBottomLeftResizing = this.manageBottomLeftResizing.bind(this);
        this.manageBottomRightResizing = this.manageBottomRightResizing.bind(this);
        this.manageTopLeftResizing = this.manageTopLeftResizing.bind(this);
        this.manageTopRightResizing = this.manageTopRightResizing.bind(this);
    }

    endDrag(e) {
        this.setState({mode: null});
        this.props.updateResizing(this.getRelativeCoordinates());
    }

    startEditing(y, x) {
        this.setState({mode: {y, x}});
    }

    manageBottomRightResizing(newX, newY) {

        if (newX < (this.state.selectionBoxOrigin[0] - this.minimumSize)
            || newY < (this.state.selectionBoxOrigin[1] - this.minimumSize))
            return;

        this.setState({
            selectionBoxTarget: [newX, newY],
        });

    }

    manageBottomLeftResizing(newX, newY) {

        if (newX > (this.state.selectionBoxTarget[0] - this.minimumSize)
            || newY < (this.state.selectionBoxOrigin[1] - this.minimumSize))
            return;

        this.setState({
            selectionBoxTarget: [this.state.selectionBoxTarget[0], newY],
            selectionBoxOrigin: [newX, this.state.selectionBoxOrigin[1]],
        });


    }

    manageTopRightResizing(newX, newY) {
        if (newX < (this.state.selectionBoxOrigin[0] - this.minimumSize)
            || newY > (this.state.selectionBoxTarget[1] - this.minimumSize))
            return;

        this.setState({
            selectionBoxTarget: [newX, this.state.selectionBoxTarget[1]],
            selectionBoxOrigin: [this.state.selectionBoxOrigin[0], newY],
        });

    }

    manageTopLeftResizing(newX, newY) {
        if (newX > (this.state.selectionBoxTarget[0] - this.minimumSize)
            || newY > (this.state.selectionBoxTarget[1] - this.minimumSize))
            return;

        this.setState({
            selectionBoxOrigin: [newX, newY],
        });

    }

    dragging(e) {
        if (!this.state.mode) return;

        const re = e.currentTarget.getBoundingClientRect(),
            methodName = 'manage' + pascalCase(this.state.mode.y + '.' + this.state.mode.x) + 'Resizing';

        // Call the corresponding manageYXResizing event handler
        this[methodName](e.nativeEvent.clientX - re.left, e.nativeEvent.clientY - re.top);
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
