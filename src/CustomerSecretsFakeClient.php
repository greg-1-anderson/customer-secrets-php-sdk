<?php

namespace PantheonSystems\CustomerSecrets;

/**
 *
 */

define("DEFAULT_TEMP_DIR", getenv('TMPDIR') ?? "/tmp");

/**
 *
 */
class CustomerSecretsFakeClient extends CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
  /*
   * @var \PantheonSystems\CustomerSecrets\SecretListInterface
   */
  /**
   * @var \PantheonSystems\CustomerSecrets\SecretList
   */
    protected SecretList $secretList;

  /**
   * @param bool $refresh *
   *
   * @return mixed
   */
    public function getSecrets(bool $refresh = false): array
    {
        return $this->secretList->getSecrets();
    }

  /**
   * @param mixed $secrets
   */
    public function setSecrets(SecretListInterface $secrets): void
    {
        $this->secrets = $secrets;
    }

  /**
   * File to store the fake secrets.
   *
   * @var string
   */
    protected string $file;

  /**
   * CustomerSecretsClient constructor.
   *
   * @param $args array
   *   Options for the fake client.
   *     - file: If not provided, default to value of env var
   *   CUSTOMER_SECRETS_FAKE_FILE or /tmp/secrets.json
   */
    public function __construct(array $args = [])
    {
        parent::__construct();
        $file = null;
        if (!empty($args['file'])) {
            $file = $args['file'];
        }
        if (!$file && getenv('CUSTOMER_SECRETS_FAKE_FILE')) {
            $file = getenv('CUSTOMER_SECRETS_FAKE_FILE');
        }
        if (!$file) {
            $file = DEFAULT_TEMP_DIR . '/secrets.json';
        }
        $this->file = $file;
        $this->secretList = new SecretList();
    }

  /**
   * Get secrets file.
   */
    public function getFilepath(): string
    {
        return $this->file;
    }

  /**
   * @param string $filepath
   *
   * @return void
   */
    public function setFilepath(string $filepath): void
    {
        $this->file = $filepath;
    }

  /**
   * Fetches secret data for current site.
   *
   * @throws \Exception
   */
    public function fetchSecrets(): void
    {
        if (file_exists($this->file)) {
            $this->secretList = SecretList::fromJson(file_get_contents($this->getFilepath()));
        }
    }
}
