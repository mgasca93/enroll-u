@extends('layouts.dashboard-layout')
@section('title','Dashboard')
@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <!-- Sales Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">Total <span>| Products payments</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $total_products }}</h6>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card revenue-card">

              <div class="card-body">
                <h5 class="card-title">Total <span>| Payments</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>${{ $total_payments }}</h6>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card customers-card">

              <div class="card-body">
                <h5 class="card-title">Total <span>| Customers Registered</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $total_customers }}</h6>
                  </div>
                </div>

              </div>
            </div>

          </div><!-- End Customers Card -->
        </div>
      </div>
      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Reports -->
          <div class="col-12">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Reports <span>/Top Five Payments By Customers</span></h5>
                 <!-- Column Chart -->
                <div id="columnChart"></div>
              </div>

            </div>
          </div><!-- End Reports -->

        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-12">
        <!-- Website Traffic -->
        <div class="card">

          <div class="card-body pb-0">
            <h5 class="card-title">Most Selled <span>| Products</span></h5>

            <!-- Pie Chart -->
            <div id="pieChart"></div>
            <!-- End Pie Chart -->

          </div>
        </div><!-- End Website Traffic -->

      </div><!-- End Right side columns -->

    </div>
  </section>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      new ApexCharts(document.querySelector("#columnChart"), {
        series: {!! $data_barras !!},
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: {!! $data_categories !!}
        },
        yaxis: {
          title: {
            text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function(val) {
              return "$ " + val + " thousands"
            }
          }
        }
      }).render();
    });

    document.addEventListener("DOMContentLoaded", () => {
      new ApexCharts(document.querySelector("#pieChart"), {
        series: {!! $data_porcentage !!},
        chart: {
          height: 350,
          type: 'pie',
          toolbar: {
            show: true
          }
        },
        labels: {!! $data_producst_name !!}
      }).render();
    });
  </script>
  <!-- End Column Chart -->
@endsection