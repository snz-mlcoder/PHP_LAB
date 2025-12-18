CREATE TABLE IF NOT EXISTS donations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NULL,
  amount_cents INT NOT NULL,
  currency VARCHAR(10) NOT NULL,
  stripe_session_id VARCHAR(255) NOT NULL,
  stripe_payment_intent_id VARCHAR(255) NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'paid',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_session (stripe_session_id)
);
