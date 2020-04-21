import React, {Component} from 'react'
import ReactDOM from 'react-dom';
import styles from '../../../sass/components/Tagger.scss'
import SelectableArea from './SelectableArea'
import ZoomableArea from '../ZoomableArea'
import {Button, Icon, Heading} from 'react-bulma-components'
import BoundingBox from './BoundingBox';
import HierarchySelector from '../HierarchySelector';

export default class Tagger extends Component {

    constructor(props) {
        super(props);

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

            modal: false,

            tagging: null,

            zoom: {
                scale: 1,
                position: {
                    top: 0,
                    left: 0,
                },
            },
        };

        this.portals = document.getElementById('portals');

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
        this.updateBox = this.updateBox.bind(this);
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
        this.toggleModal = this.toggleModal.bind(this);
        this.handleTagging = this.handleTagging.bind(this);
        this.tagSelected = this.tagSelected.bind(this);
        this.updateBoxTaggable = this.updateBoxTaggable.bind(this);
    }

    toggleModal() {
        this.setState({modal: !this.state.modal})
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

        const alreadyExists = this.state.boxes.find(e => e.top === coords.top && e.left === coords.left
            && e.width === coords.width && e.height === coords.height);

        if (alreadyExists) return;


        const bb = {
            persisted: false,
            top: coords.top,
            left: coords.left,
            width: coords.width,
            height: coords.height,
            user: this.props.user,
        };


        this.setState(state => {
            const boxes = state.boxes.concat(bb);
            return {boxes}
        }, () => {
            axios.post(route('async.bounding_boxes.store', {image: this.props.imageKey}), bb).then(({data}) => {
                this.persistBoundingBox(data);
            });
        })


    }

    persistBoundingBox({id, top, left, width, height}) {
        this.setState(state => {
            const boxes = state.boxes
                .filter(el => !(el.width === width && el.height === height && el.top === top && el.left === left))
                .concat({id, top, left, width, height, persisted: true, user: this.props.user});

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

    updateBox(id, newBox) {

        this.setState(state => {
            const boxes = state.boxes
                .filter(el => !(el.id === id))
                .concat({id, ...newBox, persisted: false, user: this.props.user});

            return {boxes};
        }, () => {
            axios.post(route('async.bounding_boxes.update', {boundingBox: id}), {
                ...newBox,
                _method: 'PATCH'
            }).then(({data}) => {
                const {top, left, width, height} = data;
                this.persistBoundingBox({id, top, left, width, height});
            });
        });

    }


    handleTagging(tagging) {
        this.setState({tagging})
        this.toggleModal()
    }

    renderBoxName(box) {

        if(!box.taggable) {
            return (<em>{Lang.trans('tagger.untagged')}</em>)
        }
        return box.taggable.name;
    }

    renderBoundingBoxList() {
        return (
            <div className={styles.bbList}>
                <Heading className={styles.label}>{this.props.lang.box_list}</Heading>
                {!this.state.boxes.length && (
                    <span className="has-text-grey">{this.props.lang.no_boxes}</span>
                )}
                {this.state.boxes.map((box, i) => (
                    <div className={styles.boxInfo} key={i} onMouseEnter={this.highlightBox.bind(this, box.id)}
                         onMouseLeave={this.unhighlightBox}>
                        <div className={styles.boxIcon}><i className="fas fa-question"/></div>
                        <div className={styles.boxContent}>
                            <div>
                                {this.renderBoxName(box)}
                                {!box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}/>)}
                            </div>
                            <div className={styles.author}>
                                {this.props.lang.by} <strong>{box.user}</strong>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        )
    }


    renderBoundingBoxes() {
        return this.state.boxes.map((box, i) => (
            <BoundingBox key={i} highlighted={box.id === this.state.highlightedBox} box={box}
                         handleTagging={this.handleTagging}
                         editable={this.state.mode === 'edit'} updateBox={(newBox) => this.updateBox(box.id, newBox)}/>
        ))
    }

    renderToolbox() {
        return (
            <div className={styles.toolbox} style={{height: '45px'}}>
                {/* Zoom buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button rounded={true} onClick={() => this.modifyScale(-.1)} size="small" className={styles.button}
                            title={this.props.lang.zoom_out}>
                        <Icon><i className="fas fa-search-minus"/></Icon>
                    </Button>
                    <Button rounded={true} onClick={() => this.modifyScale(+.1)} size="small" className={styles.button}
                            title={this.props.lang.zoom_in}>
                        <Icon><i className="fas fa-search-plus"/></Icon>
                    </Button>
                </Button.Group>

                {/* Move buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button rounded={true} onClick={() => this.modifyPosition(10, 0)} size="small"
                            className={styles.button}
                            title={this.props.lang.left}>
                        <Icon><i className="fas fa-arrow-left"/></Icon>
                    </Button>

                    <Button rounded={true} onClick={() => this.modifyPosition(0, 10)} size="small"
                            className={styles.button}
                            title={this.props.lang.up}>
                        <Icon><i className="fas fa-arrow-up"/></Icon>
                    </Button>

                    <Button rounded={true} onClick={() => this.modifyPosition(0, -10)} size="small"
                            className={styles.button}
                            title={this.props.lang.down}>
                        <Icon><i className="fas fa-arrow-down"/></Icon>
                    </Button>

                    <Button rounded={true} onClick={() => this.modifyPosition(-10, 0)} size="small"
                            className={styles.button}
                            title={this.props.lang.right}>
                        <Icon><i className="fas fa-arrow-right"/></Icon>
                    </Button>

                </Button.Group>


                {/* Sizing buttons */}
                <Button.Group className={styles.buttonGroup} hasAddons={true}>
                    <Button rounded={true} onClick={() => this.setScaleToFit()} size="small" className={styles.button}
                            disabled={this.getFitScale() === this.state.zoom.scale} title={this.props.lang.scale_fit}>
                        <Icon><i className="fas fa-compress-alt"/></Icon>
                    </Button>

                    <Button rounded={true} onClick={() => this.updateScale(1)} size="small" className={styles.button}
                            disabled={this.state.zoom.scale === 1} title={this.props.lang.scale_expand}>
                        <Icon><i className="fas fa-expand-alt"/></Icon>
                    </Button>
                </Button.Group>

                <Button rounded={true} onClick={() => this.moveTo(0, 0)} size="small" className={styles.button}
                        disabled={this.state.zoom.position.left === 0 && this.state.zoom.position.top === 0}
                        title={this.props.lang.restore_position}>
                    <Icon><i className="fas fa-crosshairs"/></Icon>
                </Button>

                {/* Mode switcher */}
                <Button.Group className={styles.buttonGroup} hasAddons={true} style={{marginLeft: 'auto'}}>
                    <Button rounded={true} onClick={() => this.setMode('draw')} size="small" className={styles.button}
                            color={this.state.mode === 'draw' ? 'link' : null} title={this.props.lang.draw_mode}>
                        <Icon><i className="fas fa-expand"/></Icon>
                    </Button>
                    <Button rounded={true} onClick={() => this.setMode('edit')} size="small" className={styles.button}
                            color={this.state.mode === 'edit' ? 'link' : null} title={this.props.lang.edit_mode}>
                        <Icon><i className="fas fa-pen"/></Icon>
                    </Button>
                    <Button rounded={true} onClick={() => this.setMode('zoom')} size="small" className={styles.button}
                            color={this.state.mode === 'zoom' ? 'link' : null} title={this.props.lang.zoom_mode}>
                        <Icon><i className="fas fa-mouse-pointer"/></Icon>
                    </Button>
                </Button.Group>
            </div>
        )
    }

    updateBoxTaggable(id, taggable) {
        this.setState(state => {
            const box = state.boxes.find(box => box.id === id);

            const boxes = state.boxes
                .filter(el => !(el.id === id))
                .concat({...box, taggable});

            return {boxes};
        });

    }

    tagSelected(taggable) {
        const requestData = {
            id: taggable.id,
            type: taggable.type,
        }
        axios.post(route('async.bounding_boxes.tag', {boundingBox: this.state.tagging}), requestData)
            .then(_ => {
                this.updateBoxTaggable(this.state.tagging, taggable)
            })
        this.toggleModal()

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

    renderModeArea() {
        return (
            <>
                <SelectableArea onMouseUp={this.createBoundingBox} disabled={this.state.mode !== 'draw'}/>
                <ZoomableArea onZoomIn={this.zoomIn} onZooming={this.zooming} onMoving={this.moving}
                              disabled={this.state.mode !== 'zoom'}/>
            </>)
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
            <>
                <div className={styles.wrapper} style={{height: this.canvasSize.height + 45 + 'px'}}>
                    <div>
                        {this.renderCanvas()}
                        {this.renderToolbox()}
                    </div>

                    {this.renderBoundingBoxList()}
                </div>
                {this.renderModal()}
            </>
        )
    }

    renderModal() {
        return ReactDOM.createPortal((
            <div className={`modal${this.state.modal ? ' is-active' : ''}`}>
                <div className="modal-background" onClick={this.toggleModal}/>
                <div className="modal-content">
                   <HierarchySelector mode="tagging" onSelection={this.tagSelected}/>
                </div>
                <button className="modal-close is-large" aria-label="close" onClick={this.toggleModal}/>
            </div>
        ), this.portals)
    }
}

const el = document.getElementById('tagger');
if (el) {
    ReactDOM.render(<Tagger image={el.dataset.image} imageKey={el.dataset.imageKey}
                            createBbLink={el.dataset.createBbLink} user={el.dataset.user}
                            boxes={JSON.parse(el.dataset.boxes)} lang={JSON.parse(el.dataset.lang)}/>, el);
}


