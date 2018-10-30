import React, { Component } from "react";
import ReactDOM from "react-dom";

export default class Wallet extends Component {
  state = {
    balance: null
  };

  componentDidMount() {
    axios.get("/dashboard/wallet-balance").then(({ data }) => {
      this.setState({ balance: data });
    });
  }
  render() {
    const { balance } = this.state;

    return (
      <div className="col-md-3 col-sm-6 col-xs-12">
        <div className="info-box">
          <span className="info-box-icon bg-green">
            <i className="fas fa-wallet" />
          </span>
          <div className="info-box-content">
            <span className="info-box-text">Wallet Balance</span>
            {balance ? (
              <span className="info-box-number"># {balance}</span>
            ) : (
              <span className="info-box-number">
                <i className="fas fa-spin fa-spinner" /> Loading......
              </span>
            )}
          </div>
        </div>
      </div>
    );
  }
}

if (document.getElementById("wallet")) {
  ReactDOM.render(<Wallet />, document.getElementById("wallet"));
}
