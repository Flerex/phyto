import React, {Component} from 'react'
import ReactDOM from 'react-dom';
import Tippy from '@tippyjs/react';

export default class ButtonWithConfirmation extends Component {
    constructor(props) {
        super(props);

        this.state = {
            modal: false,
        };

        this.toggleModal = this.toggleModal.bind(this);
        this.renderModal = this.renderModal.bind(this);

        this.portals = document.getElementById('portals');

    }

    toggleModal(e) {
        e.preventDefault();
        this.setState(state => ({modal: !state.modal}));
    }

    renderHiddenInputs() {
        return (
            <>
                <input type="hidden" name="_token"
                       value={document.querySelector('meta[name="csrf-token"]').content}/>

                {this.props.method && (
                    <input type="hidden" name="_method" value={this.props.method}/>
                )}
            </>
        )
    }

    renderModal() {
        return ReactDOM.createPortal((
            <form action={this.props.route} method="POST">
                {this.renderHiddenInputs()}

                <div className={`modal${this.state.modal ? ' is-active' : ''}`}>
                    <div className="modal-background" onClick={this.toggleModal}/>
                    <div className="modal-card">
                        <header className="modal-card-head">
                            <p className="modal-card-title">{Lang.trans('general.confirmation')}</p>
                            <button className="delete" onClick={this.toggleModal}/>
                        </header>
                        <section className="modal-card-body">
                            {Lang.trans('general.are_you_sure')}
                        </section>
                        <footer className="modal-card-foot has-text-right">
                            <button type="submit"
                                    className={`button is-${this.props.type}`}>{this.props.action}</button>
                            <button className="button" onClick={this.toggleModal}>{Lang.trans('general.close')}</button>
                        </footer>
                    </div>
                </div>
            </form>
        ), this.portals);

    }

    render() {
        return (
            <>
                <Tippy content={this.props.action}>
                    <button onClick={this.toggleModal} className={`button is-rounded is-small is-${this.props.type} is-light`}>
                        <span className="icon"><i className={this.props.icon}/></span>
                    </button>
                </Tippy>
                {this.renderModal()}
            </>
        );
    }
}

document.querySelectorAll('.button-confirmation').forEach(el => {
    ReactDOM.render(<ButtonWithConfirmation type={el.dataset.type} route={el.dataset.route}
                                            action={el.dataset.action} icon={el.dataset.icon}
                                            method={el.dataset.method}/>, el);
});
