import React, {Component} from 'react'
import styles from '../../sass/components/BoundingBox.scss'
import BoundingBoxOptions from './BoundingBoxOptions';
import Tippy from '@tippyjs/react';
import EditableArea from './EditableArea';

export default class BoundingBox extends Component {

    constructor(props) {
        super(props);

        this.state = {
            resizing: false,
            options: false,
        }

        this.getBoundingBoxStyle = this.getBoundingBoxStyle.bind(this);
        this.enableResizing = this.enableResizing.bind(this);
        this.cancelResizing = this.cancelResizing.bind(this);
        this.updateResizing = this.updateResizing.bind(this);
        this.renderResizing = this.renderResizing.bind(this);
        this.saveResizing = this.saveResizing.bind(this);
        this.updateOptions = this.updateOptions.bind(this);
    }


    getBoundingBoxStyle(box) {
        return {
            width: this.props.box.width + 'px',
            height: this.props.box.height + 'px',
            top: this.props.box.top + 'px',
            left: this.props.box.left + 'px',
        }
    }

    renderResizing() {
        if (!this.state.resizing) return;

        return (<EditableArea box={this.props.box} updateResizing={this.updateResizing}/>);
    }

    enableResizing() {
        this.setState({resizing: true, options: true});
    }

    cancelResizing() {
        this.setState({resizing: false});
    }

    updateResizing(resizing) {
        this.setState({resizing});
    }

    saveResizing() {
        this.props.updateBox(this.state.resizing);
        this.setState({resizing: false, options: false});
    }

    updateOptions(options) {
        if (this.state.resizing) return;
        this.setState({options});
    }

    render() {
        const className = styles.boundingBox + (this.props.highlighted ? ' ' + styles.highlightedBox : '')
            + (this.props.editable ? ' ' + styles.hoverable : '') + (this.state.resizing ? ' ' + styles.resizing : ''),

            options = (<BoundingBoxOptions enableResizing={this.enableResizing} box={this.props.box}
                                           cancelResizing={this.cancelResizing}
                                           saveResizing={this.saveResizing}
                                           handleRemove={this.props.handleRemove}/>);

        return (
            <>
                {this.renderResizing()}
                <Tippy content={options} visible={this.state.options} hideOnClick={false} appendTo={document.body}
                       animation="fade" interactive={true} arrow={true}>
                    <div className={className} style={this.getBoundingBoxStyle()}
                         onClick={() => this.updateOptions(!this.state.options)}/>
                </Tippy>
            </>
        )
    }


}
