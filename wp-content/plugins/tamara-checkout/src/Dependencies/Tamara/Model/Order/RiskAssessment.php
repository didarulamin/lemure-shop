<?php

namespace Tamara\Wp\Plugin\Dependencies\Tamara\Model\Order;

class RiskAssessment
{
	public const
		ACCOUNT_CREATION_DATE = 'account_creation_date',
		HAS_DELIVERED_ORDER = 'has_delivered_order',
		TOTAL_ORDER_COUNT = 'total_order_count',
		DATE_OF_FIRST_TRANSACTION = 'date_of_first_transaction',
		IS_EXISTING_CUSTOMER = 'is_existing_customer',
		IS_EMAIL_VERIFIED = 'is_email_verified',
		ORDER_AMOUNT_LAST_3_MONTHS = 'order_amount_last3months',
		ORDER_COUNT_LAST_3_MONTHS = 'order_count_last3months';

	/**
	 * @var string
	 */
	private $accountCreationDate;

	/**
	 * @var bool
	 */
	private $hasDeliveredOrder;

	/**
	 * @var int
	 */
	private $totalOrderCount;

	/**
	 * @var string
	 */
	private $dateOfFirstTransaction;

	/**
	 * @var bool
	 */
	private $isExistingCustomer;

	/**
	 * @var bool
	 */
	private $isEmailVerified;

	/**
	 * @var float
	 */
	private $orderAmountLast3months;

	/**
	 * @var int
	 */
	private $orderCountLast3months;

	public function setAccountCreationDate( ?string $accountCreationDate )
	{
		$this->accountCreationDate = $accountCreationDate;

		return $this;
	}

	public function getAccountCreationDate(): ?string {
		return $this->accountCreationDate;
	}

	public function setHasDeliveredOrder( ?bool $hasDeliveredOrder )
	{
		$this->hasDeliveredOrder = $hasDeliveredOrder;

		return $this;
	}

	public function getHasDeliveredOrder(): ?bool
	{
		return $this->hasDeliveredOrder;
	}

	public function setIsEmailVerified( ?bool $isEmailVerified )
	{
		$this->isEmailVerified = $isEmailVerified;

		return $this;
	}

	public function getIsEmailVerified(): ?bool
	{
		return $this->isEmailVerified;
	}

	public function setTotalOrderCount( ?int $totalOrderCount )
	{
		$this->totalOrderCount = $totalOrderCount;

		return $this;
	}

	public function getTotalOrderCount(): ?int
	{
		return $this->totalOrderCount;
	}

	public function setDateOfFirstTransaction( ?string $dateOfFirstTransaction )
	{
		$this->dateOfFirstTransaction = $dateOfFirstTransaction;

		return $this;
	}

	public function getDateOfFirstTransaction(): ?string
	{
		return $this->dateOfFirstTransaction;
	}

	public function setIsExistingCustomer( ?bool $isExistingCustomer )
	{
		$this->isExistingCustomer = $isExistingCustomer;

		return $this;
	}

	public function getIsExistingCustomer(): ?bool
	{
		return $this->isExistingCustomer;
	}

	public function setOrderAmountLast3months( ?float $orderAmountLast3months )
	{
		$this->orderAmountLast3months = $orderAmountLast3months;

		return $this;
	}

	public function getOrderAmountLast3months(): ?float
	{
		return $this->orderAmountLast3months;
	}

	public function setOrderCountLast3months( ?int $orderCountLast3months )
	{
		$this->orderCountLast3months = $orderCountLast3months;

		return $this;
	}

	public function getOrderCountLast3months(): ?int
	{
		return $this->orderCountLast3months;
	}

	public function toArray(): array
	{
		return [
			self::ACCOUNT_CREATION_DATE      => $this->getAccountCreationDate(),
			self::HAS_DELIVERED_ORDER        => $this->getHasDeliveredOrder(),
			self::TOTAL_ORDER_COUNT          => $this->getTotalOrderCount(),
			self::DATE_OF_FIRST_TRANSACTION  => $this->getDateOfFirstTransaction(),
			self::IS_EXISTING_CUSTOMER       => $this->getIsExistingCustomer(),
			self::IS_EMAIL_VERIFIED       => $this->getIsEmailVerified(),
			self::ORDER_AMOUNT_LAST_3_MONTHS => $this->getOrderAmountLast3months(),
			self::ORDER_COUNT_LAST_3_MONTHS  => $this->getOrderCountLast3months(),
		];
	}
}
