# Add your own checks

It's quits simple to add your own checks to your application. Add a class extending from `whatwedo\MonitoringBundle\Check`.

```
namespace AppBundle\Check;

use whatwedo\MonitoringBundle\Check;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\Skip;
use ZendDiagnostics\Result\Success;

/**
 * Class DummyCheck
 * @package AppBundle\Check
 */
class DummyCheck extends AbstractCheck
{

    /**
     * Perform the actual check and return a ResultInterface
     *
     * @return ResultInterface
     */
    public function check()
    {
        // Test doctrine is available, if not skip check
        if (!$this->has('doctrine')) {
            return new Skip('Doctrine is not available.');
        }

        if (/* your check criteria*/) {
            return new Failure('Dummy check failed');
        }
        
        // Test run successfully
        return new Success();
    }

    /**
     * Return a label describing this test instance.
     *
     * @return string
     */
    public function getLabel()
    {
        return 'Dummy check';
    }
}
```

Secondly, you have to define your class as a service with the `whatwedo.monitoring.check` tag.

```
app.check.dummy:
    class: AppBundle\Check\DummyCheck
    arguments: ['@service_container']
    tags:
        - { name: whatwedo.monitoring.check }
```
