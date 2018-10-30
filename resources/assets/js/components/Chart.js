import React, { Component } from "react";
import ReactDOM from "react-dom";
import { Line as LineChart } from "react-chartjs";
import Chart from "chart.js";

export default class Wallet extends Component {
  constructor() {
    super();
    Chart.defaults.global.responsive = true;
  }
  state = {
    data: [],
    fectching: false,
    filter: "year",
    chartOptions: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true
            }
          }
        ]
      }
    }
  };

  componentDidMount() {
    this.fetchData();
  }

  fetchData() {
    this.setState({ fectching: true });
    axios
      .get("/dashboard/sms/data", {
        params: {
          filter: this.state.filter
        }
      })
      .then(({ data: { data } }) => {
        // console.log(data);
        this.setState(
          state => ({
            data: {
              labels: data.map(
                d => (state.filter === "year" ? d.name : d.date)
              ),
              datasets: [
                {
                  label: "No Of SMS",
                  data: data.map(d => d.count),
                  borderWidth: 1
                }
              ]
            }
          }),
          () => {
            this.setState({ fectching: false });
          }
        );
      });
  }

  onChangeFilter(filter) {
    this.setState({ filter }, () => this.fetchData());
  }

  render() {
    const { fectching, filter, data } = this.state;

    return (
      <div className="col-sm-12">
        <div className="box box-success">
          <div className="box-header with-border">
            <h3 className="box-title">SMS Chart</h3>
            <div>
              <button
                type="button"
                onClick={() => this.onChangeFilter("month")}
                className={`btn btn-box-tool ${filter === "month" && "active"}`}
              >
                This Month
              </button>
              <button
                type="button"
                onClick={() => this.onChangeFilter("year")}
                className={`btn btn-box-tool ${filter === "year" && "active"}`}
              >
                This Year
              </button>
            </div>
            <div className="box-tools pull-right">
              <button
                type="button"
                className="btn btn-box-tool"
                data-widget="collapse"
              >
                <i className="fa fa-minus" />
              </button>
            </div>
          </div>
          <div className="box-body">
            <div className="chart">
              {fectching ? (
                <div
                  style={{
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center"
                  }}
                >
                  <i className="fas fa-spinner fa-spin" />
                  <span>&nbsp;Loading.....</span>
                </div>
              ) : (
                <LineChart data={data} options={this.state.chartOptions} />
              )}
            </div>
          </div>
        </div>
      </div>
    );
  }
}

if (document.getElementById("chart")) {
  ReactDOM.render(<Wallet />, document.getElementById("chart"));
}
