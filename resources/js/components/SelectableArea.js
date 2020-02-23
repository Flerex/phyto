import React, {Component} from 'react';
import styles from '../../sass/components/SelectableArea.scss'

export default class SelectableArea extends Component {

    constructor(props) {
        super(props);

        this.animationInProgress = null;

        this.state = {
            hold: false,
            selectionBox: false,
            selectionBoxOrigin: [0, 0],
            selectionBoxTarget: [0, 0],
            animation: ""
        };

        this.handleTransformBox = this.handleTransformBox.bind(this);
        this.beginSelection = this.beginSelection.bind(this);
        this.getRelativeCoordinates = this.getRelativeCoordinates.bind(this);
        this.endSelection = this.endSelection.bind(this);
        this.dragging = this.dragging.bind(this);
        this.containerStyle = this.containerStyle.bind(this);
    }

    handleTransformBox() {

        const {selectionBoxOrigin, selectionBoxTarget} = this.state;

        if (selectionBoxOrigin[1] > selectionBoxTarget[1] &&
            selectionBoxOrigin[0] > selectionBoxTarget[0])
            return 'scaleY(-1) scaleX(-1)';

        if (selectionBoxOrigin[1] > selectionBoxTarget[1]) return 'scaleY(-1)';
        if (selectionBoxOrigin[0] > selectionBoxTarget[0]) return 'scaleX(-1)';

        return null;
    }

    endSelection(e) {
        if (this.props.onMouseUp && this.state.selectionBox) {
            this.props.onMouseUp(e, this.getSelectionCoordinates());
        }

        this.setState({
            hold: false,
            animation: styles.selectionFadeOut
        });

        this.animationInProgress = setTimeout(() => {

            this.setState({
                selectionBox: false,
                animation: '',
            });

            this.animationInProgress = null;

        }, 300);
    }

    beginSelection(e) {

        if (this.props.disabled) return;

        let doubleClick = false;

        clearTimeout(this.animationInProgress);
        this.animationInProgress = null;

        this.setState({selectionBox: false, animation: ''});

        if (this.state.animation.length > 0 && e.target.id === 'react-rectangle-selection') {
            this.setState({selectionBox: false, animation: ''});
            doubleClick = true;
        }

        this.setState({
            hold: true,
            selectionBoxOrigin: [e.nativeEvent.offsetX, e.nativeEvent.offsetY],
            selectionBoxTarget: [e.nativeEvent.offsetX, e.nativeEvent.offsetY]
        });
    }

    dragging(e) {
        if (this.state.hold && !this.state.selectionBox) {
            if (this.props.onMouseDown) this.props.onMouseDown();
            this.setState({selectionBox: true});
        }
        if (this.state.selectionBox && !this.animationInProgress) {

            this.setState({
                selectionBoxTarget: [e.nativeEvent.offsetX, e.nativeEvent.offsetY]
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
            display: this.props.disabled ? 'none' : 'block'
        }
    }


    render() {
        const baseStyle = Object.assign({
            transform: this.handleTransformBox()
        }, this.getRelativeCoordinates());

        return (
            <div
                style={this.containerStyle()}
                onMouseMove={this.dragging}
                onMouseUp={this.endSelection}
                onMouseLeave={this.endSelection}
                onMouseDown={this.beginSelection}
            >
                {this.state.selectionBox && (
                    <div className={`${this.state.animation} ${styles.selection}`}
                         id={'react-rectangle-selection'}
                         style={Object.assign(baseStyle, this.props.style)}/>
                )}
                {this.props.children}
            </div>
        );
    }
}
