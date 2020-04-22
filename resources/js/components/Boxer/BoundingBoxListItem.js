import React, {Component} from 'react'
import styles from '../../../sass/components/Boxer/BoundingBoxListItem.scss'

export default class BoundingBoxListItem extends Component {

    constructor(props) {
        super(props);
    }

    render(box) {
        return (
            <div className={styles.boxInfo} onMouseEnter={() => this.props.highlightBox(this.props.box.id)}
                 onMouseLeave={this.props.unhighlightBox}>
                <div className={styles.boxIcon}><i className="fas fa-question"/></div>
                <div>
                    <div>
                        <em>{Lang.trans('boxer.untagged')}</em>
                        {!this.props.box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}/>)}
                    </div>
                    <div className={styles.author}>
                        {Lang.trans('boxer.by')} <strong>{this.props.box.user}</strong>
                    </div>
                </div>
            </div>
        )
    }
}
