import 'bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

function getChartData(id) {
    const element = document.getElementById(id);

    if (!element) {
        return null;
    }

    try {
        return JSON.parse(element.textContent);
    } catch (error) {
        console.error(`Invalid chart data: ${id}`, error);
        return null;
    }
}

function initializeDashboardCharts() {
    const attendance = getChartData('attendance-chart-data');
    const status = getChartData('status-chart-data');
    const course = getChartData('course-chart-data');
    const sms = getChartData('sms-chart-data');

    const attendanceCanvas = document.getElementById('attendanceChart');

    if (attendanceCanvas && attendance) {
        new Chart(attendanceCanvas, {
            type: 'line',
            data: {
                labels: attendance.labels,
                datasets: [
                    {
                        label: 'Present',
                        data: attendance.present,
                        borderColor: '#269b5a',
                        backgroundColor: 'rgba(38,155,90,.10)',
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: 'Late',
                        data: attendance.late,
                        borderColor: '#e5a323',
                        backgroundColor: 'rgba(229,163,35,.08)',
                        fill: true,
                        tension: 0.35
                    },
                    {
                        label: 'Absent',
                        data: attendance.absent,
                        borderColor: '#e34d4d',
                        backgroundColor: 'rgba(227,77,77,.06)',
                        fill: true,
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 7,
                            padding: 18,
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f0f1f3'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    const statusCanvas = document.getElementById('statusChart');

    if (statusCanvas && status) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: status.labels,
                datasets: [{
                    data: status.values,
                    backgroundColor: [
                        '#269b5a',
                        '#e5a323',
                        '#e34d4d',
                        '#4382d8'
                    ],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 15,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }

    const courseCanvas = document.getElementById('courseChart');

    if (courseCanvas && course) {
        new Chart(courseCanvas, {
            type: 'bar',
            data: {
                labels: course.labels,
                datasets: [{
                    label: 'Attendance Records',
                    data: course.values,
                    backgroundColor: '#f47721',
                    borderRadius: 6,
                    maxBarThickness: 35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f0f1f3'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    const smsCanvas = document.getElementById('smsChart');

    if (smsCanvas && sms) {
        new Chart(smsCanvas, {
            type: 'line',
            data: {
                labels: sms.labels,
                datasets: [
                    {
                        label: 'Queued / Submitted',
                        data: sms.queued,
                        borderColor: '#e5a323',
                        tension: 0.35
                    },
                    {
                        label: 'Delivered',
                        data: sms.delivered,
                        borderColor: '#269b5a',
                        tension: 0.35
                    },
                    {
                        label: 'Failed',
                        data: sms.failed,
                        borderColor: '#e34d4d',
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 7,
                            padding: 15,
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f0f1f3'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

function initializeLayoutControls() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (toggle && sidebar && backdrop) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('show');
        });

        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        });
    }

    const passwordToggle = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            passwordToggle.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initializeLayoutControls();
    initializeDashboardCharts();
});