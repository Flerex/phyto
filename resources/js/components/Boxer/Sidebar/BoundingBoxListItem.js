import React, {useEffect} from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxListItem.scss'
import {highlightBox} from '../store/actions/boxes';
import {useDispatch, useSelector} from 'react-redux';

export default function BoundingBoxListItem({box}) {

    const image = useSelector(s => s.image);
    const dispatch = useDispatch();

    const ref = React.createRef();


    useEffect(() => {
        if (!box.focused) return

        const element = ref.current;
        const parent = ref.current.parentNode;

        if (parent.scrollTop > element.offsetTop || (parent.scrollTop + parent.offsetHeight) <= element.offsetTop) {
            parent.scrollTop = element.offsetTop - 10;
        }

    }, [box]);

    const highlight = highlighted => {
        dispatch(highlightBox(box, highlighted));
    }

    const previewStyle = () => {

        const minProperty = Math.min(box.width, box.height);

        return {
            width: box.width + 'px',
            height: box.height + 'px',
            backgroundImage: 'url(\'' + image + '\')',
            backgroundPosition: -box.left + 'px ' + -box.top + 'px',
            transform: 'scale(' + 50 / minProperty + ')',
        }
    }

    const renderBoxName = () => {
      if(!box.taggable)
          return (<i>{Lang.trans('boxer.untagged')}</i>)

      return box.taggable.name
    };

    const className = styles.boxInfo + (box.focused ? ' ' + styles.focused : '');

    return (
        <div className={className} ref={ref} onMouseEnter={() => highlight(true)} onMouseLeave={() => highlight(false)}>
            <div className={styles.boxPreview}>
                <div className={styles.previewBoundingBox} style={previewStyle()}/>
            </div>
            <div>
                <div>
                    {renderBoxName()}
                    {!box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}/>)}
                </div>
                <div className={styles.author}>{Lang.trans('boxer.by')} <strong>{box.user.name}</strong></div>
            </div>
        </div>
    )
}
