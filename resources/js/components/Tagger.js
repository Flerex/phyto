import React, {Component} from 'react'
import ReactDOM from "react-dom";
import styles from '../../sass/components/Tagger.scss'
import SelectableArea from './SelectableArea'
import ZoomableArea from './ZoomableArea'
import {Button, Icon} from 'react-bulma-components'

export default class Tagger extends Component {

    constructor(props) {
        super(props)

        // Constants
        this.canvasSize = {
            width: 768,
            height: 600,
        };

        // Class
        this.tagger = React.createRef();

        // State
        this.state = {
            taggerDimensions: null,
            boxes: props.boxes.map(b => Object.assign(b, {persisted: true})),
            highlightedBox: null,
            mode: 'zoom',

            zoom: {
                scale: 1,
                position: {
                    top: 0,
                    left: 0,
                },
            },
        };

        // Method bindings
        this.getImageSize = this.getImageSize.bind(this);
        this.getCanvasStyle = this.getCanvasStyle.bind(this);
        this.createBoundingBox = this.createBoundingBox.bind(this);
        this.persistBoundingBox = this.persistBoundingBox.bind(this);
        this.highlightBox = this.highlightBox.bind(this);
        this.unhighlightBox = this.unhighlightBox.bind(this);
        this.renderModeArea = this.renderModeArea.bind(this);
        this.renderBoundingBoxList = this.renderBoundingBoxList.bind(this);
        this.renderBoundingBoxes = this.renderBoundingBoxes.bind(this);
        this.renderToolbox = this.renderToolbox.bind(this);
        this.setMode = this.setMode.bind(this);
        this.getBoundingBoxStyle = this.getBoundingBoxStyle.bind(this);
        this.zoomIn = this.zoomIn.bind(this);
        this.zooming = this.zooming.bind(this);
        this.moving = this.moving.bind(this);
        this.modifyScale = this.modifyScale.bind(this);
        this.getTaggerStyle = this.getTaggerStyle.bind(this);
        this.updateScale = this.updateScale.bind(this);
        this.setScaleToFit = this.setScaleToFit.bind(this);
        this.getFitScale = this.getFitScale.bind(this);
        this.moveTo = this.moveTo.bind(this);
    }


    componentDidMount() {
        this.getImageSize(this.props.image).then(res => {
            const {x, y} = this.tagger.current.getBoundingClientRect(),
                taggerDimensions = Object.assign({x, y}, res);
            this.setState({taggerDimensions})
        })
    }

    getImageSize(url) {
        return new Promise(resolve => {
            const img = new Image();
            img.onload = function () {
                resolve({
                    width: this.width,
                    height: this.height,
                });
            };
            img.src = url;
        })
    }

    getBoundingBoxStyle(box) {
        return {
            width: box.width + 'px',
            height: box.height + 'px',
            top: box.top + 'px',
            left: box.left + 'px',
        }
    }

    getCanvasStyle() {
        return {
            width: this.canvasSize.width,
            height: this.canvasSize.height,
        }
    }


    createBoundingBox(e, coords) {

        if (coords.width <= 5 || coords.height <= 5) return;


        const bb = {
            persisted: false,
            top: coords.top,
            left: coords.left,
            width: coords.width,
            height: coords.height,
        };

        this.setState(state => {
            const boxes = state.boxes.concat(bb);
            return {boxes}
        }, () => {
            axios.post(this.props.createBbLink, bb).then(({data}) => {
                this.persistBoundingBox(data);
            });
        })


    }

    persistBoundingBox({id, top, left, width, height}) {
        this.setState(state => {
            const boxes = state.boxes
                .filter(el => !(el.width === width && el.height === height && el.top === top && el.left === left))
                .concat({id, top, left, width, height, persisted: true});

            return {boxes};
        })
    }

    highlightBox(highlightedBox) {
        this.setState({highlightedBox})
    }

    unhighlightBox() {
        this.setState({highlightedBox: null})
    }

    setMode(mode) {
        this.setState({mode})
    }

    renderModeArea() {
        return (
            <>
                <SelectableArea onMouseUp={this.createBoundingBox} disabled={this.state.mode !== 'draw'}/>
                <ZoomableArea onZoomIn={this.zoomIn} onZooming={this.zooming} onMoving={this.moving}
                              disabled={this.state.mode !== 'zoom'}/>
            </>)

    }

    updateScale(absoluteValue) {
        this.setState(state => {
            const zoom = {...state.zoom};

            zoom.scale = Math.max(Math.min(2, absoluteValue), .125);

            return {zoom};
        })
    }

    modifyScale(value) {
        this.updateScale(this.state.zoom.scale + value)
    }

    getFitScale() {
        if (!this.state.taggerDimensions) return null;

        const greatestProperty = this.state.taggerDimensions.height > this.state.taggerDimensions.width ? 'height' : 'width';

        return this.canvasSize[greatestProperty] / this.state.taggerDimensions[greatestProperty];
    }

    setScaleToFit() {
        this.updateScale(this.getFitScale())
    }

    moveTo(x, y) {
        this.setState(state => {
            const zoom = {...state.zoom};

            zoom.position.left = x;
            zoom.position.top = y;

            return {zoom};
        });
    }

    modifyPosition(x, y) {
        this.setState(state => {
            const zoom = {...state.zoom};

            zoom.position.left += x;
            zoom.position.top += y;

            return {zoom};
        })
    }

    zoomIn() {
        this.modifyScale(+.4)
    }

    zooming(mode, delta) {
        this.modifyScale(delta * -0.01)
    }

    moving(movementX, movementY) {
        this.moveTo(this.state.zoom.position.left + movementX, this.state.zoom.position.top + movementY)
    }

    renderBoundingBoxList() {
        return (
            <div className={styles.bbList}>
                {this.state.boxes.map((box, i) => (
                    <div className={styles.boxInfo} key={i} onMouseEnter={this.highlightBox.bind(this, box.id)}
                         onMouseLeave={this.unhighlightBox}>
                        <div className={styles.boxIcon}><i className="fas fa-question"/></div>
                        <div className={styles.boxContent}><em>Untagged species</em>
                            {!box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}></i>)}
                        </div>
                    </div>
                ))}
            </div>
        )
    }

    renderBoundingBoxes() {
        return this.state.boxes.map((box, i) => (
            <div
                className={`${styles.boundingBox}  ${(box.id === this.state.highlightedBox) ? styles.highlightedBox : ''}`}
                key={i} style={this.getBoundingBoxStyle(box)}/>
        ))
    }

    renderToolbox() {
        return (
            <div className={styles.toolbox} style={{height: '45px'}}>
                {/* Zoom buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button onClick={() => this.modifyScale(-.1)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-search-minus"/></Icon>
                    </Button>

                    <Button onClick={() => this.modifyScale(+.1)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-search-plus"/></Icon>
                    </Button>
                </Button.Group>

                {/* Move buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button onClick={() => this.modifyPosition(10, 0)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-arrow-left"/></Icon>
                    </Button>

                    <Button onClick={() => this.modifyPosition(0, 10)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-arrow-up"/></Icon>
                    </Button>

                    <Button onClick={() => this.modifyPosition(0, -10)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-arrow-down"/></Icon>
                    </Button>

                    <Button onClick={() => this.modifyPosition(-10, 0)} size="small" className={styles.button}>
                        <Icon><i className="fas fa-arrow-right"/></Icon>
                    </Button>

                </Button.Group>


                {/* Sizing buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button onClick={() => this.setScaleToFit()} size="small" className={styles.button}
                            disabled={this.getFitScale() === this.state.zoom.scale}>
                        <Icon><i className="fas fa-compress-alt"/></Icon>
                    </Button>

                    <Button onClick={() => this.updateScale(1)} size="small" className={styles.button}
                            disabled={this.state.zoom.scale === 1}>
                        <Icon><i className="fas fa-expand-alt"/></Icon>
                    </Button>
                </Button.Group>

                <Button onClick={() => this.moveTo(0, 0)} size="small" className={styles.button}
                        disabled={this.state.zoom.position.left === 0 && this.state.zoom.position.top === 0}>
                    <Icon><i className="fas fa-crosshairs"/></Icon>
                </Button>

                {/* Mode switcher */}
                <Button.Group className={styles.buttonGroup} hasAddons={true} style={{marginLeft: 'auto'}}>
                    <Button onClick={() => this.setMode('draw')}
                            color={this.state.mode === 'draw' ? 'primary' : 'light'}
                            size="small" className={styles.button}>
                        <Icon><i className="fas fa-expand"/></Icon>
                    </Button>
                    <Button onClick={() => this.setMode('zoom')}
                            color={this.state.mode === 'zoom' ? 'primary' : 'light'}
                            size="small" className={styles.button}>
                        <Icon><i className="fas fa-mouse-pointer"/></Icon>
                    </Button>
                </Button.Group>
            </div>
        )
    }

    getTaggerStyle() {
        return {
            transform: 'scale(' + this.state.zoom.scale + ')',
            marginTop: this.state.zoom.position.top + 'px',
            marginLeft: this.state.zoom.position.left + 'px',
            width: this.state.taggerDimensions ? this.state.taggerDimensions.width : null,
            height: this.state.taggerDimensions ? this.state.taggerDimensions.height : null,
            backgroundImage: 'url(' + this.props.image + ')',
        }
    }

    renderCanvas() {
        return (
            <div className={styles.canvas} style={this.getCanvasStyle()}>
                <div className={styles.tagger} ref={this.tagger} style={this.getTaggerStyle()}>
                    {this.renderModeArea()}
                    {this.renderBoundingBoxes()}
                </div>
            </div>
        )
    }

    render() {
        return (
            <div className={styles.wrapper} style={{height: this.canvasSize.height + 45 + 'px'}}>
                <div>
                    {this.renderCanvas()}
                    {this.renderToolbox()}
                </div>

                {this.renderBoundingBoxList()}
            </div>
        )
    }
}

const el = document.getElementById('tagger');
if (el) {
    ReactDOM.render(<Tagger image={el.dataset.image} createBbLink={el.dataset.createBbLink}
                            boxes={JSON.parse(el.dataset.boxes)}/>, el);
}


