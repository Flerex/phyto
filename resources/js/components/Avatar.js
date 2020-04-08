import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import Avatars from '@dicebear/avatars';
import sprites from '@dicebear/avatars-bottts-sprites';

export default class Avatar extends Component {

    constructor(props) {
        super(props);

        const avatars = new Avatars(sprites, {
            background: 'transparent',
        });

        this.state = {
            svg: avatars.create(props.username),
        }

    }


    render() {

        return (<div dangerouslySetInnerHTML={{__html: this.state.svg}}/>);
    }


}


document.querySelectorAll('.user-avatar').forEach(el => {
    ReactDOM.render(<Avatar username={el.dataset.username}/>, el);
})
