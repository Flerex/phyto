import React, {Component} from 'react'
import styles from '../../../sass/components/Boxer/BoundingBoxList.scss'
import {Heading} from 'react-bulma-components';
import BoundingBoxListItem from './BoundingBoxListItem';

export default class BoundingBoxList extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className={styles.bbList}>
                <Heading className={styles.label}>{Lang.trans('boxer.box_list')}</Heading>
                {!this.props.boxes.length && (
                    <span className="has-text-grey">{Lang.trans('boxer.no_boxes')}</span>
                )}
                {this.props.boxes.map((box, i) => (
                    <BoundingBoxListItem key={i} box={box} highlightBox={this.props.highlightBox}
                                         unhighlightBox={this.props.unhighlightBox}/>))}
            </div>
        )
    }


}
