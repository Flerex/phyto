import React, {Component} from 'react';
import styles from '../../sass/components/SelectableArea.scss'

export default class SelectableArea extends Component {

    constructor(props) {
        super(props);

        this.animationInProgress = null;

        this.state = {
            areaPosition: [0, 0],
            hold: false,
            selectionBox: false,
            selectionBoxOrigin: [0, 0],
            selectionBoxTarget: [0, 0],
            animation: ""
        };

        this.area = React.createRef();

        this.handleTransformBox = this.handleTransformBox.bind(this);
        this.handleMouseDown = this.handleMouseDown.bind(this);
        this.getRelativeCoordinates = this.getRelativeCoordinates.bind(this);
        this.closeSelectionBox = this.closeSelectionBox.bind(this);
        this.dragging = this.dragging.bind(this);
        this.containerStyle = this.containerStyle.bind(this);
    }

    componentDidMount() {
        const coords = this.area.current.getBoundingClientRect();
        // We add .scrollY because .getBoundingClient() computes values with respect to the window, not the document
        const [left, top] = [coords.left, coords.top + window.scrollY];
        this.setState({areaPosition: [left, top]});
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

    closeSelectionBox(e) {
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

    handleMouseDown(e) {

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
            selectionBoxOrigin: [e.nativeEvent.pageX, e.nativeEvent.pageY],
            selectionBoxTarget: [e.nativeEvent.pageX, e.nativeEvent.pageY]
        });
    }

    dragging(e) {
        if (this.state.hold && !this.state.selectionBox) {
            if (this.props.onMouseDown) this.props.onMouseDown();
            this.setState({selectionBox: true});
        }
        if (this.state.selectionBox && !this.animationInProgress) {

            this.setState({
                selectionBoxTarget: [e.nativeEvent.pageX, e.nativeEvent.pageY]
            });
        }

    }


    getRelativeCoordinates() {

        const {selectionBoxOrigin, selectionBoxTarget, areaPosition} = this.state;

        return {
            left: Math.abs(selectionBoxOrigin[0] - areaPosition[0]),
            top: Math.abs(selectionBoxOrigin[1] - areaPosition[1]),
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
                style={this.containerStyle()} ref={this.area}
                onMouseLeave={this.closeSelectionBox} onMouseDown={this.handleMouseDown}
                onMouseUp={this.closeSelectionBox} onMouseMove={this.dragging}>
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
