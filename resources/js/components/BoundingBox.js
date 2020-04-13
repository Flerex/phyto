import React, {Component} from 'react'
import styles from '../../sass/components/BoundingBox.scss'
import BoundingBoxOptions from './BoundingBoxOptions';
import Tippy from '@tippyjs/react';

export default class BoundingBox extends Component {

    constructor(props) {
        super(props);

        this.getBoundingBoxStyle = this.getBoundingBoxStyle.bind(this);
    }


    getBoundingBoxStyle(box) {
        return {
            width: box.width + 'px',
            height: box.height + 'px',
            top: box.top + 'px',
            left: box.left + 'px',
        }
    }

    render() {
        const className = styles.boundingBox + (this.props.highlighted ? ' ' + styles.highlightedBox : '')
            + (this.props.hoverable ? ' ' + styles.hoverable : '');

        return (
            <Tippy content={<BoundingBoxOptions box={this.props.box} enableResizing={this.props.enableResizing}/>} appendTo={document.body} animation="fade"
                   interactive={true} visible={true} arrow={true}>
                <div className={className} style={this.getBoundingBoxStyle(this.props.box)}
                />
            </Tippy>
        )
    }


}
