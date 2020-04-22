import React, {Component} from 'react'
import styles from '../../../sass/components/Boxer/BoundingBoxList.scss'
import {Heading} from 'react-bulma-components';

export default class BoundingBoxList extends Component {

    constructor(props) {
        super(props);
        this.renderItem = this.renderItem.bind(this);
    }

    renderItem(box, i) {
        return (
            <div className={styles.boxInfo} key={i} ref={box.ref} onMouseEnter={() => this.props.highlightBox(box.id)}
                 onMouseLeave={this.unhighlightBox}>
                <div className={styles.boxIcon}><i className="fas fa-question"/></div>
                <div>
                    <div>
                        <em>{Lang.trans('boxer.untagged')}</em>
                        {!box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}/>)}
                    </div>
                    <div className={styles.author}>
                        {Lang.trans('boxer.by')} <strong>{box.user}</strong>
                    </div>
                </div>
            </div>
        )
    }


    render() {
        return (
            <div className={styles.bbList}>
                <Heading className={styles.label}>{Lang.trans('boxer.box_list')}</Heading>
                {!this.props.boxes.length && (
                    <span className="has-text-grey">{Lang.trans('boxer.no_boxes')}</span>
                )}
                {this.props.boxes.map((box, i) => this.renderItem(box, i))}
            </div>
        )
    }


}
