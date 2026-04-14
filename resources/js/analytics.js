document.addEventListener('DOMContentLoaded', function () {
    if (!window.analyticsData) {
        console.error('Analytics data not found');
        return;
    }

    const data = window.analyticsData;

    // Penelitian per Tahun (Line Chart)
    const penelitianPerTahunCtx = document.getElementById('penelitianPerTahunChart').getContext('2d');
    new Chart(penelitianPerTahunCtx, {
        type: 'line',
        data: {
            labels: data.penelitianPerTahun.labels,
            datasets: [{
                label: 'Jumlah Penelitian',
                data: data.penelitianPerTahun.data,
                borderColor: '#1e3a8a',
                backgroundColor: 'rgba(30, 58, 138, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#1e3a8a',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Penelitian',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Distribusi Skema Penelitian (Pie Chart)
    const skemaPenelitianCtx = document.getElementById('skemaPenelitianChart').getContext('2d');
    new Chart(skemaPenelitianCtx, {
        type: 'pie',
        data: {
            labels: data.skemaPenelitian.labels,
            datasets: [{
                data: data.skemaPenelitian.data,
                backgroundColor: [
                    '#1e3a8a',
                    '#166534',
                    '#991b1b',
                    '#6b21a8',
                    '#92400e',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });

    // Top 5 Dosen Penelitian (Bar Chart)
    const topDosenCtx = document.getElementById('topDosenChart').getContext('2d');
    new Chart(topDosenCtx, {
        type: 'bar',
        data: {
            labels: data.topDosen.labels,
            datasets: [{
                label: 'Jumlah Penelitian',
                data: data.topDosen.data,
                backgroundColor: '#1e3a8a',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Penelitian',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Dosen',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Pengabdian per Tahun (Line Chart)
    const pengabdianPerTahunCtx = document.getElementById('pengabdianPerTahunChart').getContext('2d');
    new Chart(pengabdianPerTahunCtx, {
        type: 'line',
        data: {
            labels: data.pengabdianPerTahun.labels,
            datasets: [{
                label: 'Jumlah Pengabdian',
                data: data.pengabdianPerTahun.data,
                borderColor: '#166534',
                backgroundColor: 'rgba(22, 101, 52, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#166534',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pengabdian',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Distribusi Skema Pengabdian (Pie Chart)
    const skemaPengabdianCtx = document.getElementById('skemaPengabdianChart').getContext('2d');
    new Chart(skemaPengabdianCtx, {
        type: 'pie',
        data: {
            labels: data.skemaPengabdian.labels,
            datasets: [{
                data: data.skemaPengabdian.data,
                backgroundColor: [
                    '#166534',
                    '#1e3a8a',
                    '#991b1b',
                    '#6b21a8',
                    '#92400e',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });

    // Top 5 Dosen Pengabdian (Bar Chart)
    const topDosenPengabdianCtx = document.getElementById('topDosenPengabdianChart').getContext('2d');
    new Chart(topDosenPengabdianCtx, {
        type: 'bar',
        data: {
            labels: data.topDosenPengabdian.labels,
            datasets: [{
                label: 'Jumlah Pengabdian',
                data: data.topDosenPengabdian.data,
                backgroundColor: '#166534',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pengabdian',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Dosen',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // HAKI per Tahun (Line Chart)
    const hakiPerTahunCtx = document.getElementById('hakiPerTahunChart').getContext('2d');
    new Chart(hakiPerTahunCtx, {
        type: 'line',
        data: {
            labels: data.hakiPerTahun.labels,
            datasets: [{
                label: 'Jumlah HAKI',
                data: data.hakiPerTahun.data,
                borderColor: '#6b21a8',
                backgroundColor: 'rgba(107, 33, 168, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#6b21a8',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah HAKI',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Distribusi Status HAKI (Pie Chart)
    const statusHakiCtx = document.getElementById('statusHakiChart').getContext('2d');
    new Chart(statusHakiCtx, {
        type: 'pie',
        data: {
            labels: data.statusHaki.labels,
            datasets: [{
                data: data.statusHaki.data,
                backgroundColor: [
                    '#6b21a8',
                    '#991b1b',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });

    // Top 5 Dosen HAKI (Bar Chart)
    const topDosenHakiCtx = document.getElementById('topDosenHakiChart').getContext('2d');
    new Chart(topDosenHakiCtx, {
        type: 'bar',
        data: {
            labels: data.topDosenHaki.labels,
            datasets: [{
                label: 'Jumlah HAKI',
                data: data.topDosenHaki.data,
                backgroundColor: '#6b21a8',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah HAKI',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Dosen',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Paten per Tahun (Line Chart)
    const patenPerTahunCtx = document.getElementById('patenPerTahunChart').getContext('2d');
    new Chart(patenPerTahunCtx, {
        type: 'line',
        data: {
            labels: data.patenPerTahun.labels,
            datasets: [{
                label: 'Jumlah Paten',
                data: data.patenPerTahun.data,
                borderColor: '#92400e',
                backgroundColor: 'rgba(146, 64, 14, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#92400e',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Paten',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Distribusi Jenis Paten (Pie Chart)
    const jenisPatenCtx = document.getElementById('jenisPatenChart').getContext('2d');
    new Chart(jenisPatenCtx, {
        type: 'pie',
        data: {
            labels: data.jenisPaten.labels,
            datasets: [{
                data: data.jenisPaten.data,
                backgroundColor: [
                    '#92400e',
                    '#1e3a8a',
                    '#166534',
                    '#6b21a8',
                    '#991b1b',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });

    // Top 5 Dosen Paten (Bar Chart)
    const topDosenPatenCtx = document.getElementById('topDosenPatenChart').getContext('2d');
    new Chart(topDosenPatenCtx, {
        type: 'bar',
        data: {
            labels: data.topDosenPaten.labels,
            datasets: [{
                label: 'Jumlah Paten',
                data: data.topDosenPaten.data,
                backgroundColor: '#92400e',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Paten',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Dosen',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});