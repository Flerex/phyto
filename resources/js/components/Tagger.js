import React, {Component} from 'react'
import ReactDOM from "react-dom";
import styles from '../../sass/components/Tagger.scss'
import SelectableArea from './SelectableArea'
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
        this.tagger = React.createRef()

        // State
        this.state = {
            taggerDimensions: null,
            boxes: props.boxes.map(b => Object.assign(b, {persisted: true})),
            highlightedBox: null,
            mode: 'draw',
        }

        // Method bindings
        this.getImageSize = this.getImageSize.bind(this)
        this.getCanvasStyle = this.getCanvasStyle.bind(this)
        this.createBoundingBox = this.createBoundingBox.bind(this)
        this.persistBoundingBox = this.persistBoundingBox.bind(this)
        this.highlightBox = this.highlightBox.bind(this)
        this.unhighlightBox = this.unhighlightBox.bind(this)
        this.renderModeArea = this.renderModeArea.bind(this)
        this.renderBoundingBoxList = this.renderBoundingBoxList.bind(this)
        this.renderBoundingBoxes = this.renderBoundingBoxes.bind(this)
        this.renderDrawMode = this.renderDrawMode.bind(this)
        this.renderToolbox = this.renderToolbox.bind(this)
        this.renderImage = this.renderImage.bind(this)
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
            }
            img.src = url;
        })
    }

    static getBoundingBoxStyle(box) {
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

        const bb = Object.assign({
            persisted: false,
        }, coords);

        axios.post(this.props.createBbLink, bb).then(({data}) => {
            this.persistBoundingBox(data);
        });
        this.setState(state => {
            const boxes = state.boxes.concat(coords)
            return {boxes}
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

    renderModeArea() {
        if (!this.state.taggerDimensions) return;

        const methodName = 'render' + this.state.mode.charAt(0).toUpperCase() + this.state.mode.slice(1) + 'Mode';

        return this[methodName].call()

    }

    renderDrawMode() {
        return (<SelectableArea onMouseUp={this.createBoundingBox}/>)
    }

    renderZoomMode() {
        return (<ZoomableArea onMouseUp={this.movePicture}/>)
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
                key={i} style={Tagger.getBoundingBoxStyle(box)}/>
        ))
    }

    renderToolbox() {
        return (
            <div className={styles.toolbox} style={{height: '45px'}}>
                <Button color={this.state.mode === 'draw' ? 'primary' : 'light'} size="small" className={styles.button}>
                    <Icon><i className="fas fa-draw-polygon"></i></Icon>
                </Button>
                <Button color={this.state.mode === 'zoom' ? 'primary' : 'light'} size="small" className={styles.button}>
                    <Icon><i className="fas fa-search-plus"></i></Icon>
                </Button>
            </div>
        )
    }

    renderImage() {
        return (
            <img src={this.props.image} className={styles.imageBg}/>
        )
    }

    render() {
        return (
            <div className={styles.wrapper} style={{height: this.canvasSize.height + 45 + 'px'}}>
                <div className={styles.taggerContainer}>
                    <div className={styles.canvas} style={this.getCanvasStyle()}>
                        <div className={styles.tagger} ref={this.tagger}>
                            {this.renderImage()}
                            {this.renderModeArea()}
                            {this.renderBoundingBoxes()}
                        </div>
                    </div>
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


