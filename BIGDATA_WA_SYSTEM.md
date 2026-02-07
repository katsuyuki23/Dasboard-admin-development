# 📊 Big Data Analytics + WhatsApp Bot System

## Overview

Sistem terintegrasi yang menggabungkan **Machine Learning Analytics** dengan **WhatsApp Bot** untuk memberikan insight cerdas dan engagement otomatis kepada donatur.

---

## 🧠 Machine Learning Features

### 1. Donation Prediction
**Algoritma:** Linear Regression  
**Input:** Historical donations (6 months)  
**Output:** Predicted donation amount for next month  
**Confidence:** R-squared score (%)

**API Endpoint:**
```
GET /api/predictions/donations
```

**Response:**
```json
{
  "predicted_amount": 5000000,
  "historical_data": [...],
  "confidence": 85.5
}
```

### 2. Donor Churn Detection
**Method:** Rule-based scoring (0-100)  
**Factors:**
- Days since last donation (40%)
- Donation frequency (30%)
- Recent activity trend (30%)

**Risk Levels:**
- HIGH: Score ≥ 75
- MEDIUM: Score 50-74
- LOW: Score < 50

**API Endpoint:**
```
GET /api/predictions/churn
```

### 3. Expense Anomaly Detection
**Method:** Statistical (Z-Score)  
**Threshold:** |Z| > 2 (2 standard deviations)  
**Types:**
- OVERSPENDING: Z > 2
- UNDERSPENDING: Z < -2

**API Endpoint:**
```
GET /api/predictions/anomalies
```

### 4. Cash Flow Forecasting
**Horizon:** 3 months ahead  
**Components:**
- Predicted income (from donation forecast)
- Average expenses (3-month moving average)
- Projected balance

**API Endpoint:**
```
GET /api/predictions/cashflow
```

---

## 💬 WhatsApp Bot Commands

### Public Commands (Everyone)
| Command | Description |
|---------|-------------|
| `HALO` / `HI` | Greeting message |
| `MENU` | Show all available commands |
| `REKENING` | Bank account information |
| `CARA` | How to donate guide |
| `ANAK` | Info about children |
| `DAFTAR` | Registration info |

### Donor Commands (Registered Only)
| Command | Description | Requires Auth |
|---------|-------------|---------------|
| `INFO` | Personal donation history | ✅ |
| `DAMPAK` | Impact of your donations | ✅ |
| `LAPORAN` | Monthly financial report | ✅ |
| `SALDO` | Cash flow forecast | ✅ |

### Authentication Flow
1. Donor sends first WhatsApp message
2. Bot checks `donatur_whatsapp` table
3. If not registered → Show registration info
4. If registered → Full access to donor commands

**Admin Registration:**
```sql
INSERT INTO donatur_whatsapp (id_donatur, whatsapp_number, is_verified, verified_at)
VALUES (1, '6281234567890', 1, NOW());
```

---

## 📅 Automated Monthly Reports

### Schedule
**Frequency:** Monthly  
**Date:** 1st of every month  
**Time:** 08:00 AM

### Recipients
- All donors with registered WhatsApp
- Active in last 3 months (donated recently)

### Report Content
- Total donations received
- Total expenses by category
- Number of active children
- Programs running

### Manual Testing
```bash
php artisan reports:monthly --test
```
(Sends to admin only)

### Production Run
```bash
php artisan reports:monthly
```

---

## 🔌 API Endpoints

### Predictions
```
GET /api/predictions/donations      # Donation forecast
GET /api/predictions/cashflow       # Cash flow forecast
GET /api/predictions/churn          # Donor churn analysis
GET /api/predictions/segments       # Donor segmentation
GET /api/predictions/anomalies      # Expense anomalies
GET /api/predictions/donor/{id}/ltv # Donor lifetime value
```

### Analytics Dashboard
```
GET /analytics                      # Main dashboard view
GET /api/analytics                  # Dashboard data (JSON)
```

---

## 🚀 Deployment Guide

### 1. Setup WhatsApp (Fonnte)
```
1. Daftar di https://fonnte.com (GRATIS)
2. Login → Dapatkan Token
3. Connect Device → Scan QR Code
4. Status: Connected ✅
```

### 2. Configure Environment
```env
# .env
FONNTE_TOKEN=your-fonnte-token-here
WHATSAPP_ADMIN_NUMBER=6281234567890
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Test Analytics
```bash
# Test donation prediction
curl http://localhost:8000/api/predictions/donations

# Test WhatsApp notification
http://localhost:8000/test-wa
```

### 5. Setup Scheduler (Production)
Add to crontab:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📈 Dashboard Widgets

### Current Widgets
1. **Predicted Donation** (Next Month)
2. **Donation Trends** (Line Chart - 12 months)
3. **Donor Segmentation** (Pie Chart)
4. **Stunting Risk Statistics** (Progress Bars)

### Data Sources
- MySQL (Operational data)
- DuckDB (Analytics data via ETL)
- ML Services (Predictions)

---

## 🧪 Testing

### Test Donation Prediction
```bash
# Ensure you have at least 3 months of donation data
php artisan tinker
>>> $service = new \App\Services\PredictionService();
>>> $result = $service->predictNextMonthDonations();
>>> print_r($result);
```

### Test Churn Detection
```bash
php artisan tinker
>>> $service = new \App\Services\DonorAnalyticsService();
>>> $atRisk = $service->detectChurnRisk();
>>> print_r($atRisk);
```

### Test WhatsApp Bot
1. Send "MENU" to your WhatsApp bot
2. Try commands: INFO, DAMPAK, LAPORAN, SALDO
3. Verify responses

### Test Monthly Report
```bash
php artisan reports:monthly --test
```

---

## 🔧 Troubleshooting

### WhatsApp Not Sending
1. Check Fonnte dashboard: https://fonnte.com/dashboard
2. Verify device status: **Connected**
3. Check token in `.env` file
4. Check logs: `storage/logs/laravel.log`
5. Test endpoint: `http://127.0.0.1:8000/test-wa`

### "Invalid Token" Error
- Copy token ulang dari dashboard Fonnte
- Pastikan tidak ada spasi di `.env`
- Format: `FONNTE_TOKEN=xxxxx-xxxxx-xxxxx`

### "Quota Exceeded"
- Paket gratis: 100 pesan/hari
- Tunggu besok atau upgrade paket di Fonnte

### Prediction Returns Null
- Ensure at least 3 months of historical data
- Check database connection
- Verify data in `donasi` table

### Scheduler Not Running
- Verify cron is set up
- Check Laravel logs: `storage/logs/laravel.log`
- Test manually: `php artisan schedule:run`

---

## 📚 Code Structure

```
app/
├── Services/
│   ├── PredictionService.php          # ML predictions
│   ├── DonorAnalyticsService.php      # Churn & segmentation
│   ├── AnomalyDetectionService.php    # Anomaly detection
│   ├── DonorInsightService.php        # Personalized messages
│   ├── WhatsAppBotService.php         # Bot logic
│   └── WhatsAppNotificationService.php # WAHA integration
├── Http/Controllers/
│   └── PredictionController.php       # API endpoints
└── Console/Commands/
    └── SendMonthlyReports.php         # Automated reports

database/migrations/
└── 2026_01_29_231049_create_donatur_whatsapp_table.php

routes/
├── web.php                            # Web & API routes
└── console.php                        # Scheduler config
```

---

## 🎓 For Academic Defense

### Key Points to Highlight:
1. **Custom ML Implementation** (no external libraries)
2. **Real-time Analytics** (DuckDB ETL pipeline)
3. **Automated Engagement** (WhatsApp bot + monthly reports)
4. **Scalable Architecture** (Docker, microservices)
5. **Data-Driven Insights** (Predictions, churn detection, anomalies)

### Metrics to Show:
- Prediction accuracy (R-squared)
- Churn detection precision
- Bot response time
- Report delivery success rate

---

**Developed by:** [Your Name]  
**Project:** Panti Asuhan Assholihin Management System  
**Tech Stack:** Laravel 11, DuckDB, WAHA, Chart.js
