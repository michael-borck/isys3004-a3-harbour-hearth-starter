import React, { useState } from 'react';
import _ from 'lodash';
import { format } from 'date-fns';

const PRESET_AMOUNTS = [25, 50, 100, 200];

export default function GiftCardWidget() {
    const [amount, setAmount] = useState(50);
    const [customAmount, setCustomAmount] = useState('');
    const [frequency, setFrequency] = useState('one-time');
    const [submitting, setSubmitting] = useState(false);
    const [thanks, setThanks] = useState(false);
    const [error, setError] = useState('');
    const [orderedAt, setOrderedAt] = useState(null);

    const isCustom = amount === 'custom';
    const effectiveAmount = isCustom
        ? _.clamp(Number(customAmount) || 0, 0, 10000)
        : amount;

    function handlePresetClick(value) {
        setAmount(value);
        setCustomAmount('');
        setError('');
    }

    function handleCustomChange(e) {
        setCustomAmount(e.target.value);
        setAmount('custom');
        setError('');
    }

    function handleSubmit(e) {
        e.preventDefault();
        if (!effectiveAmount || effectiveAmount <= 0) {
            setError('Please enter an amount greater than $0.');
            return;
        }
        if (effectiveAmount > 10000) {
            setError('For gift cards over $10,000 please contact us directly.');
            return;
        }
        setSubmitting(true);
        // Fake processing delay - no real payment integration yet.
        setTimeout(() => {
            setSubmitting(false);
            setOrderedAt(new Date());
            setThanks(true);
        }, 600);
    }

    function handleReset() {
        setThanks(false);
        setAmount(50);
        setCustomAmount('');
        setFrequency('one-time');
        setError('');
    }

    if (thanks) {
        return (
            <div className="hh-gift">
                <div className="hh-gift__thanks">
                    <h3>Gift card on its way</h3>
                    <p>
                        Your {frequency === 'monthly' ? 'monthly' : 'one-time'} gift card of{' '}
                        <strong>${effectiveAmount}</strong> is being prepared. You'll get a
                        confirmation email shortly.
                    </p>
                    <p className="hh-gift__small">
                        Logged {orderedAt ? format(orderedAt, "d MMM yyyy 'at' h:mm a") : 'just now'}.
                        (No payment was actually taken - we're still wiring up the payment provider.)
                    </p>
                    <button type="button" className="hh-gift__link" onClick={handleReset}>
                        Order another gift card
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="hh-gift">
            <form onSubmit={handleSubmit} noValidate>
                <fieldset className="hh-gift__frequency">
                    <legend>How often?</legend>
                    <label>
                        <input
                            type="radio"
                            name="frequency"
                            value="one-time"
                            checked={frequency === 'one-time'}
                            onChange={() => setFrequency('one-time')}
                        />
                        <span>One-time</span>
                    </label>
                    <label>
                        <input
                            type="radio"
                            name="frequency"
                            value="monthly"
                            checked={frequency === 'monthly'}
                            onChange={() => setFrequency('monthly')}
                        />
                        <span>Monthly</span>
                    </label>
                </fieldset>

                <fieldset className="hh-gift__amounts">
                    <legend>Amount (AUD)</legend>
                    <div className="hh-gift__presets">
                        {PRESET_AMOUNTS.map((preset) => (
                            <button
                                key={preset}
                                type="button"
                                className={
                                    'hh-gift__preset' +
                                    (amount === preset ? ' is-selected' : '')
                                }
                                onClick={() => handlePresetClick(preset)}
                            >
                                ${preset}
                            </button>
                        ))}
                    </div>
                    <label className="hh-gift__custom">
                        <span>Or enter a custom amount:</span>
                        <input
                            type="number"
                            inputMode="decimal"
                            min="1"
                            step="1"
                            placeholder="$"
                            value={customAmount}
                            onChange={handleCustomChange}
                        />
                    </label>
                </fieldset>

                {error && <p className="hh-gift__error" role="alert">{error}</p>}

                <button type="submit" disabled={submitting}>
                    {submitting
                        ? 'Processing...'
                        : `Buy gift card $${effectiveAmount || 0}${frequency === 'monthly' ? '/mo' : ''}`}
                </button>

                <p className="hh-gift__small">
                    Gift cards are valid at all Harbour &amp; Hearth locations for 12 months.
                </p>
            </form>
        </div>
    );
}
